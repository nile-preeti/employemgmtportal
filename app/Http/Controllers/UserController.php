<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::when(request()->filled("search"), function ($query) {
            $keyword = trim(request("search"));
            return $query->where("name", "LIKE", "%$keyword%")
                ->orWhere("designation", "LIKE", "%$keyword%")
                ->orWhere("email", "LIKE", "%$keyword%")
                ->orWhere("phone", "LIKE", "%$keyword%")
                ->orWhere("rep_manager", "LIKE", "%$keyword%");
        })
            ->where("role_id", 2)
            ->when(request()->filled("status"), function ($query) {
                return $query->where("status", request("status"));
            })
            ->orderBy("emp_id", "desc")
            ->paginate(15);

        // Fetch attendance counts
        foreach ($users as $user) {
            $attendance = DB::table('attendances')
                ->where('user_id', $user->id)
                ->selectRaw("
                    SUM(
                        CASE 
                            WHEN status = 'Present' THEN 1 
                            WHEN status = 'Half-day' THEN 0.5 
                            ELSE 0 
                        END
                    ) as total_days_present
                ")
                ->first();

            // If attendance is less than 1, keep decimal, else round to integer
            $user->total_days_present = ($attendance->total_days_present ?? 0) < 1
                ? $attendance->total_days_present
                : (int) $attendance->total_days_present;
        }


        $title = "Employee Management";

        return view("pages.users.index", compact("title", 'users', 'search'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required| unique:users,email',
            'password' => 'required',
            'designation' => 'required',
            'phone' => 'required',
            'emp_id' => 'required| unique:users,emp_id',
            'rep_manager' => 'nullable',


        ]);

        $user = new User();
        $user->role_id = 2;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->image = $request->image;
        $user->designation = $request->designation;
        $user->phone = $request->phone;
        $user->emp_id = $request->emp_id;
        $user->rep_manager = $request->rep_manager;


        $user->status = $request->status;

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['success' => true, 'message' => "Employee Created Successfully"]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                Rule::unique('users', 'email')->ignore($id), // Allow current user's email
            ],
            'designation' => 'required',
            'phone' => 'required',
            'emp_id' => [
                'required',
                Rule::unique('users', 'emp_id')->ignore($id), // Allow the current user's emp_id
            ],
            'rep_manager' => 'nullable',
        ]);

        $user =  User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->image = $request->image;
        $user->designation = $request->designation;
        $user->phone = $request->phone;
        $user->status = $request->status;
        $user->emp_id = $request->emp_id;
        $user->rep_manager = $request->rep_manager;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return response()->json(['success' => true, 'message' => "Employee Updated Successfully"]);
    }

    public function  destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => true, 'message' => "Employee deleted successfully"]);
        }
        return response()->json(['success' => false, 'message' => "Employee does not exists"]);
    }
    // user end routes
    public function userAttendance(Request $request, $id)
    {
        $month = request('month', date('m'));
        $year = request('year', date('Y'));
        $statusFilter = $request->query('status'); // Store status filter separately
        $user = User::where('id', $id)->first();

        // Fetch attendance records
        $attendanceRecords = Attendance::where('user_id', $id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->when($statusFilter, function ($query) use ($statusFilter) {
                return $query->where('status', $statusFilter);
            })
            ->get()
            ->keyBy('date'); // Store by date for quick lookup

        $title = "Employee Attendance Records";
        $holidays = Holiday::whereMonth('date', $month)->whereYear('date', $year)->pluck('date')->toArray();

        $allDays = [];
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $currentDate = Carbon::now()->format('Y-m-d');

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');

            if (isset($attendanceRecords[$formattedDate])) {
                $record = $attendanceRecords[$formattedDate];

                $checkInTime = !empty($record->check_in_time) ? strtotime($record->check_in_time) : null;
                $checkOutTime = !empty($record->check_out_time) ? strtotime($record->check_out_time) : null;

                if ($formattedDate === $currentDate) {
                    // ✅ If today’s check-in is available, mark as "Present"
                    $recordStatus = $checkInTime ? 'Present' : 'Absent';
                } else {
                    if (is_null($checkInTime) || is_null($checkOutTime)) {
                        $recordStatus = 'Absent'; // No check-in or check-out means Absent
                    } else {
                        // Calculate worked hours
                        $workedHours = ($checkOutTime - $checkInTime) / 3600; // Convert to hours

                        if ($workedHours < 4.5) {
                            $recordStatus = 'Absent';
                        } elseif ($workedHours >= 4.5 && $workedHours < 9) {
                            $recordStatus = 'Half-day';
                        } else {
                            $recordStatus = 'Present';
                        }
                    }
                }

                $allDays[] = [
                    'date' => $formattedDate,
                    'check_in_time' => $record->check_in_time,
                    'check_in_full_address' => $record->check_in_full_address,
                    'check_out_time' => $record->check_out_time,
                    'check_out_full_address' => $record->check_out_full_address,
                    'status' => $recordStatus
                ];
            } else {
                if (in_array($formattedDate, $holidays)) {
                    $recordStatus = 'Holiday';
                } elseif ($date->isWeekend()) {
                    $recordStatus = 'Weekly Off';
                } elseif ($formattedDate > $currentDate) {
                    $recordStatus = 'N/A';
                } else {
                    $recordStatus = 'Absent';
                }

                $allDays[] = [
                    'date' => $formattedDate,
                    'check_in_time' => null,
                    'check_in_full_address' => null,
                    'check_out_time' => null,
                    'check_out_full_address' => null,
                    'status' => $recordStatus
                ];
            }
        }

        // Apply status filter after processing
        if ($statusFilter) {
            $allDays = array_filter($allDays, function ($day) use ($statusFilter) {
                return $day['status'] === $statusFilter;
            });
        }

        // Paginate the data
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $allDaysPaginated = new LengthAwarePaginator(
            collect($allDays)->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            count($allDays),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Calculate attendance summary
        $totalWorkingDays = collect($allDays)->filter(function ($day) {
            return !in_array($day['status'], ['Holiday', 'Weekly Off', 'N/A']);
        })->count();

        $totalPresent = collect($allDays)->sum(function ($day) {
            return $day['status'] === 'Present' ? 1 : ($day['status'] === 'Half-day' ? 0.5 : 0);
        });

        $totalHalfDay = collect($allDays)->where('status', 'Half-day')->count();
        $totalAbsent = $totalWorkingDays - $totalPresent;

        return view("pages.users.attendance", compact(
            "allDaysPaginated",
            "totalWorkingDays",
            "totalPresent",
            "totalHalfDay",
            "totalAbsent",
            "title",
            'user',
        ));
    }




    public function downloadAttendanceCsv(Request $request, $id)
{
    $month = request('month', date('m'));
    $year = request('year', date('Y'));

    $user = User::findOrFail($id);
    
    $attendanceRecords = Attendance::where('user_id', $id)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->get()
        ->keyBy('date');

    $holidays = Holiday::whereMonth('date', $month)->whereYear('date', $year)->pluck('date')->toArray();

    $allDays = [];
    $startDate = Carbon::createFromDate($year, $month, 1);
    $endDate = $startDate->copy()->endOfMonth();
    $currentDate = Carbon::now()->format('Y-m-d');

    for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
        $formattedDate = $date->format('Y-m-d');

        if (isset($attendanceRecords[$formattedDate])) {
            $record = $attendanceRecords[$formattedDate];

            $checkInTime = !empty($record->check_in_time) ? $record->check_in_time : 'N/A';
            $checkOutTime = !empty($record->check_out_time) ? $record->check_out_time : 'N/A';
            $checkInAddress = !empty($record->check_in_full_address) ? $record->check_in_full_address : 'N/A';
            $checkOutAddress = !empty($record->check_out_full_address) ? $record->check_out_full_address : 'N/A';

            $checkInTimestamp = !empty($record->check_in_time) ? strtotime($record->check_in_time) : null;
            $checkOutTimestamp = !empty($record->check_out_time) ? strtotime($record->check_out_time) : null;

            if ($formattedDate === $currentDate) {
                $recordStatus = ($checkInTime !== 'N/A') ? 'Present' : 'Absent';
            } else {
                if ($checkInTime === 'N/A' || $checkOutTime === 'N/A') {
                    $recordStatus = 'Absent';
                } else {
                    $workedHours = ($checkOutTimestamp - $checkInTimestamp) / 3600;
                    $recordStatus = ($workedHours < 4.5) ? 'Absent' : (($workedHours < 9) ? 'Half-day' : 'Present');
                }
            }

            $allDays[] = [
                'Date' => $formattedDate,
                'Check-In Time' => $checkInTime,
                'Check-In Address' => $checkInAddress,
                'Check-Out Time' => $checkOutTime,
                'Check-Out Address' => $checkOutAddress,
                'Status' => $recordStatus
            ];
        } else {
            $recordStatus = in_array($formattedDate, $holidays) ? 'Holiday' :
                ($date->isWeekend() ? 'Weekly Off' :
                ($formattedDate > $currentDate ? 'N/A' : 'Absent'));

            $allDays[] = [
                'Date' => $formattedDate,
                'Check-In Time' => 'N/A',
                'Check-In Address' => 'N/A',
                'Check-Out Time' => 'N/A',
                'Check-Out Address' => 'N/A',
                'Status' => $recordStatus
            ];
        }
    }

    // Define CSV headers
    $fileName = "attendance_{$user->name}_{$month}_{$year}.csv";
    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename={$fileName}",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    // Create CSV content
    $handle = fopen('php://output', 'w');
    fputcsv($handle, array_keys($allDays[0])); // Add headers

    foreach ($allDays as $row) {
        fputcsv($handle, $row);
    }

    fclose($handle);

    return Response::make('', 200, $headers);
}



    public function attendance()
    {

        $user = Auth::user();
        //dd($user);
        // Find today's check-in record
        return view("users.attendance", compact('user'));
    }

    public function login()
    {
        if (auth()->user()) {
            return redirect(route("user.dashboard"));
        }
        return view("users.login");
    }
    public function login_post(Request $request)
    {
        // Validate the input
        $request->validate([
            'password' => 'required',
            'emp_id' => 'required|exists:users,emp_id',
        ],[
            'emp_id.exists' => "Invalid Employee Id"
        ]);

        $credentials = $request->only('emp_id', 'password');
        $user = User::where('emp_id', $request->emp_id)->first();

        // Check if the user exists
        if ($user) {
            // If it's a user (role_id != 1)
            if ($user->role_id != 1) {
                // Attempt to log in as a regular user
                if (Auth::guard('web')->attempt($credentials,true)) {
                    return response()->json([
                        'status' => 'success',
                        'redirect' => route("user.dashboard"),
                        'message' => "User logged in successfully",
                        'user' => Auth::user(),
                    ]);
                    
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid credentials',
                    ]);
                }
            }
            // If it's an admin (role_id == 1), return an error
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access. Admins cannot log in via this portal.',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ]);
        }
    }

    public function attendance_records()
    {
        $user = Auth::user();

        return view("users.attendance_records", compact('user'));
    }

    public function holidays()
    {

        $holidays = \App\Models\Holiday::whereYear('date', now()->year)
            ->orderBy('date', 'asc')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->date)->format('F Y'); // Group by month and year
            });
        // dd($holidays);
        return view("users.holidays", compact('holidays'));
    }
    public function dashboard()
{
    $currentYear = Carbon::now()->year;
    $currentMonth = Carbon::now()->month;
    $userId = auth()->id(); // Get logged-in user ID

    // Fetch all holidays for the current month
    $holidays = Holiday::whereYear('date', $currentYear)
        ->whereMonth('date', $currentMonth)
        ->pluck('date')
        ->toArray();

    $holidaysCount = Holiday::whereYear('date', $currentYear)
                    ->count();

    // Get attendance records for the user
    $attendances = Attendance::where('user_id', $userId)
        ->whereYear('date', $currentYear)
        ->whereMonth('date', $currentMonth)
        ->get()
        ->keyBy('date'); // Convert to an associative array for easy lookup

    $totalPresentDays = 0;
    $totalAbsentDays = 0;
    $totalWorkingDays = 0;

    // Debugging arrays
    $workingDays = [];
    $absentDays = [];

    // Loop through each day up to today’s date
    for ($day = 1; $day <= Carbon::now()->day; $day++) {
        $date = Carbon::createFromDate($currentYear, $currentMonth, $day)->format('Y-m-d');
        $dayOfWeek = Carbon::parse($date)->dayOfWeek; // 6 = Saturday, 0 = Sunday

        // Ignore weekends and holidays
        if (!in_array($date, $holidays) && $dayOfWeek != 6 && $dayOfWeek != 0) {
            $totalWorkingDays++; // Count as a working day
            $workingDays[] = $date; // Store working days for debugging

            if (isset($attendances[$date])) {
                $attendance = $attendances[$date];

                $checkInTime = !empty($attendance->check_in_time) ? strtotime($attendance->check_in_time) : null;
                $checkOutTime = !empty($attendance->check_out_time) ? strtotime($attendance->check_out_time) : null;

                if (is_null($checkInTime) || is_null($checkOutTime)) {
                    // No check-in or check-out recorded -> Full Absent
                    $totalAbsentDays++;
                    $absentDays[] = $date;
                } else {
                    // Calculate worked hours
                    $workedHours = ($checkOutTime - $checkInTime) / 3600; // Convert seconds to hours

                    if ($workedHours < 4.5) {
                        $totalAbsentDays++; // Less than 4.5 hours -> Full Absent
                        $absentDays[] = $date;
                    } elseif ($workedHours >= 4.5 && $workedHours < 9) {
                        $totalPresentDays += 0.5;
                        $totalAbsentDays += 0.5; // Half-day: count both present & absent
                        $absentDays[] = $date . " (Half-Day)";
                    } else {
                        $totalPresentDays++; // Full-day Present
                    }
                }
            } else {
                // No attendance record on a working day -> Full Absent
                $totalAbsentDays++;
                $absentDays[] = $date;
            }
        }
    }


    return view("users.dashboard", compact('totalWorkingDays', 'totalPresentDays', 'totalAbsentDays', 'holidaysCount'));
}





    



    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return response()->json(['success' => true]);
    }


    public function profile(Request $request)
    {
        return view("users.profile");
    }

    public function directory(Request $request)
    {
        return view("users.directory");
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->back()->with('success', 'Users imported successfully.');
    }



    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed'
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully!']);
    }


    public function help()
    {
        $user = Auth::user();

        return view("users.help", compact('user'));
    }
}
