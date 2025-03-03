<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Holiday;
use Illuminate\Support\Facades\Response;
use App\Models\Attendance;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = User::where("role_id", 2)->orderBy('emp_id', 'DESC')->get();
        $users = User::where("role_id", 2)->orderBy('emp_id', 'DESC')->take(10)->get();
        $totalHolidays = Holiday::whereYear('date', Carbon::now()->year)->count();



        return view("pages.dashboard", compact("users", "totalHolidays","user"));
    }


    public function downloadLogs()
    {
        // Fetch users where status = 1 and role_id = 2
        $users = User::where('status', 1)->where('role_id', 2)->orderby('id', 'DESC')->get();


        // Define CSV headers
        $csvHeader = ['S.No.', 'Emp ID', 'Name', 'Email', 'Phone', 'Designation', 'Reporting Manager'];

        // Convert users data to CSV format
        $csvData = [];
        $serialNo = 1;
        foreach ($users as $user) {
            $phone = $user->phone ? '+91' . $user->phone : 'N/A';
            $csvData[] = [
                $serialNo++,
                $user->emp_id ?? 'N/A',
                $user->name ?? 'N/A',
                $user->email ?? 'N/A',
                $phone,
                $user->designation ?? 'N/A',
                $user->rep_manager ?? 'N/A',
            ];
        }

        // Open memory stream for CSV
        $file = fopen('php://output', 'w');
        ob_start(); // Start output buffering
        fputcsv($file, $csvHeader); // Add headers
        foreach ($csvData as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        $csvOutput = ob_get_clean(); // Get CSV content

        // Return CSV file as a response
        return Response::make($csvOutput, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employee_logs.csv"',
        ]);
    }

    public function signin()
    {
        return view("pages.signin");
    }
    public function signin_post(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6',
        ], [
            'email.exists' => "This email is not registered with Nile."
        ]);

        try {
            $credentials = $request->only('email', 'password');
            $user = User::where("email", $request->email)->first();

            if (!$user || $user->role_id != 1) {
                return response()->json(['message' => 'Unauthorized Access.', 'status' => 401]);
            }

            if (!Auth::guard('admin')->attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials', 'status' => 401]);
            }

            // Debugging: Check if admin is authenticated
            if (Auth::guard('admin')->check()) {
                return response()->json([
                    'message' => 'Login Successful',
                    'status' => 200,
                    'user' => Auth::guard('admin')->user(), // Debugging
                    'redirect' => route('admin.dashboard')
                ]);
            } else {
                return response()->json([
                    'message' => 'Auth Failed',
                    'status' => 403
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'status' => 500]);
        }
    }
    public function profile()
    {
        $admin = auth()->user();
        return view("pages.profile", compact("admin"));
    }

    public function profile_post(Request $request)
    {

        $request->validate(['fullname' => 'required', 'email' => 'required']);
        $admin = User::find(auth()->user()->id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->image = $request->image;
        $admin->phone = $request->phone;
        $admin->save();


        return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
    }
    public function change_password(Request $request)
    {

        return view("pages.change_password");
    }
    public function change_password_post(Request $request)
    {

        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);
        $admin = User::find(auth()->user()->id);
        if (!Auth::guard("web")->attempt(['email' => $admin->email, 'password' => $request->old_password])) {
            return response()->json(['success' => false, 'message' => 'Please enter correct old password']);
        }


        $admin->password = Hash::make($request->password);

        $admin->save();


        return response()->json(['success' => true, 'message' => 'Password updated successfully']);
    }
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    // all functions related to orders listing and order details


    public function users()
    {
        $users = User::when(request()->filled("search"), function ($query) {
            $keyword = trim(request("search"));
            return $query->where("name", "LIKE", "%$keyword%")->orWhere("description", "LIKE", "%$keyword%");
        })
            ->where("role_id", 2)
            ->when(request()->filled("status"), function ($query) {
                return $query->where("status", request("status"));
            })

            ->orderBy("id", "desc")->paginate(config("contant.paginatePerPage"));

        $title = "User Management";

        return view("pages.users.index", compact("title", 'users'));
    }

    public function users_details($id)
    {
        $title = "User Management";
        $user = User::find($id);

        return view("pages.users.details", compact("title", 'user',));
    }
    public function userStatusUpdate($id)
    {
        $user = User::find($id);
        if ($user) {
            if ($user->status) {
                $user->status = 0;
            } else {

                $user->status = 1;
            }
            $user->save();
            return back()->with('success', "User Status Updated Successfully");
        }
        return back()->with('error', "User does not exists");
    }


    public function reporting(Request $request)
    {
        $title = "Reporting";
        $search = $request->input('search');
        $month = request('month', date('m'));
        $year = request('year', date('Y'));

        // Fetch users excluding role_id = 1
        $usersQuery = User::where('role_id', '!=', 1);

        // Apply search filter
        if (!empty($search)) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('emp_id', 'like', "%$search%")
                    ->orWhere('rep_manager', 'like', "%$search%");
            });
        }

        $users = $usersQuery->orderBy('emp_id', 'desc')->get();

        // Fetch holidays for the selected month & year
        $holidays = Holiday::whereMonth('date', $month)->whereYear('date', $year)->pluck('date')->toArray();

        // Define the date range
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $today = Carbon::today();

        //  Calculate total working days (excluding weekends & holidays)
        $totalWorkingDays = 0;
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            if (!in_array($formattedDate, $holidays) && !$date->isWeekend()) {
                $totalWorkingDays++;
            }
        }

        $allUserAttendance = [];

        foreach ($users as $user) {
            $totalPresent = 0;
            $totalAbsent = 0;
            $totalHalfDay = 0;
            $totalWorkingHours = 0;
            $daysConsidered = 0;

            //  If selected month is in the future, skip attendance calculations
            if ($startDate->gt($today)) {
                $allUserAttendance[] = [
                    'id' => $user->id,
                    'emp_id' => $user->emp_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'designation' => $user->designation,
                    'rep_manager' => $user->rep_manager,
                    'phone' => $user->phone,
                    'total_working_days' => $totalWorkingDays, 
                    'total_present_days' => 0, 
                    'total_absent_days' => 0,  
                    'total_working_hours' => 0, 
                ];
                continue;
            }

            //  Fetch attendance records only if month is current or past
            $attendanceRecords = Attendance::where('user_id', $user->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->get()
                ->keyBy('date');

            //  Loop through past & today’s working days
            for ($date = $startDate->copy(); $date->lte($endDate) && $date->lte($today); $date->addDay()) {
                $formattedDate = $date->format('Y-m-d');

                // Skip weekends & holidays
                if (in_array($formattedDate, $holidays) || $date->isWeekend()) {
                    continue;
                }

                $daysConsidered++;

                if (isset($attendanceRecords[$formattedDate])) {
                    $record = $attendanceRecords[$formattedDate];

                    if (!empty($record->check_in_time) && !empty($record->check_out_time)) {
                        $checkInTime = Carbon::parse($record->check_in_time);
                        $checkOutTime = Carbon::parse($record->check_out_time);

                        $workedMinutes = $checkInTime->diffInMinutes($checkOutTime);
                        $workedHours = round($workedMinutes / 60, 2);

                        $totalWorkingHours += $workedHours;

                        if ($workedHours < 4.5) {
                            $totalAbsent++;
                        } elseif ($workedHours >= 4.5 && $workedHours < 9) {
                            $totalHalfDay++;
                            $totalPresent += 0.5;
                        } else {
                            $totalPresent++;
                        }
                    } else {
                        $totalAbsent++;
                    }
                } else {
                    $totalAbsent++;
                }
            }

            $allUserAttendance[] = [
                'id' => $user->id,
                'emp_id' => $user->emp_id,
                'name' => $user->name,
                'email' => $user->email,
                'designation' => $user->designation,
                'rep_manager' => $user->rep_manager,
                'phone' => $user->phone,
                'total_working_days' => $totalWorkingDays, //  Show for future months
                'total_present_days' => $totalPresent,
                'total_absent_days' => $totalAbsent,
                'total_working_hours' => round($totalWorkingHours),
            ];
        }

        //  Convert array to collection & paginate manually
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $allUserAttendanceCollection = new Collection($allUserAttendance);
        $allUserAttendancePaginated = new LengthAwarePaginator(
            $allUserAttendanceCollection->forPage($currentPage, $perPage),
            $allUserAttendanceCollection->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('pages.reporting.reporting', [
            'allUserAttendance' => $allUserAttendancePaginated,
            'month' => $month,
            'year' => $year,
            'totalWorkingDays' => $totalWorkingDays,
            'search' => $search,
            'title' => $title,
        ]);
    }


    public function downloadCSV(Request $request)
    {
        $search = $request->input('search');
        $month = request('month', date('m'));
        $year = request('year', date('Y'));
    
        $usersQuery = User::where('role_id', '!=', 1);
    
        if (!empty($search)) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('emp_id', 'like', "%$search%")
                    ->orWhere('rep_manager', 'like', "%$search%");
            });
        }
    
        $users = $usersQuery->orderBy('emp_id', 'desc')->get();
    
        $holidays = Holiday::whereMonth('date', $month)->whereYear('date', $year)->pluck('date')->toArray();
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $today = Carbon::today();
    
        $totalWorkingDays = 0;
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            if (!in_array($date->format('Y-m-d'), $holidays) && !$date->isWeekend()) {
                $totalWorkingDays++;
            }
        }
    
        $allUserAttendance = [];
        $serialNumber = 1; // Initialize serial number
    
        foreach ($users as $user) {
            $totalPresent = 0;
            $totalAbsent = 0;
            $totalHalfDay = 0;
            $totalWorkingHours = 0;
            $daysConsidered = 0;
    
            if ($startDate->gt($today)) {
                $allUserAttendance[] = [
                    $serialNumber++, // Add serial number
                    $user->emp_id,
                    $user->name,
                    $user->email,
                    $user->designation,
                    $user->rep_manager,
                    $user->phone,
                    $totalWorkingDays,
                    0,
                    0,
                    0
                ];
                continue;
            }
    
            $attendanceRecords = Attendance::where('user_id', $user->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->get()
                ->keyBy('date');
    
            for ($date = $startDate->copy(); $date->lte($endDate) && $date->lte($today); $date->addDay()) {
                $formattedDate = $date->format('Y-m-d');
    
                if (in_array($formattedDate, $holidays) || $date->isWeekend()) {
                    continue;
                }
    
                $daysConsidered++;
    
                if (isset($attendanceRecords[$formattedDate])) {
                    $record = $attendanceRecords[$formattedDate];
    
                    if (!empty($record->check_in_time) && !empty($record->check_out_time)) {
                        $checkInTime = Carbon::parse($record->check_in_time);
                        $checkOutTime = Carbon::parse($record->check_out_time);
    
                        $workedMinutes = $checkInTime->diffInMinutes($checkOutTime);
                        $workedHours = round($workedMinutes / 60, 2);
    
                        $totalWorkingHours += $workedHours;
    
                        if ($workedHours < 4.5) {
                            $totalAbsent++;
                        } elseif ($workedHours >= 4.5 && $workedHours < 9) {
                            $totalHalfDay++;
                            $totalPresent += 0.5;
                        } else {
                            $totalPresent++;
                        }
                    } else {
                        $totalAbsent++;
                    }
                } else {
                    $totalAbsent++;
                }
            }
    
            $phoneNumber = $user->phone ? '+91' . $user->phone : 'N/A';
    
            $allUserAttendance[] = [
                $serialNumber++, // Add serial number
                $user->emp_id,
                $user->name,
                $user->email,
                $user->designation,
                $user->rep_manager,
                $phoneNumber,
                $totalWorkingDays,
                $totalPresent,
                $totalAbsent,
                round($totalWorkingHours)
            ];
        }
    
        $filename = "attendance_report_{$month}_{$year}.csv";
    
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
    
        $handle = fopen('php://output', 'w');
    
        // Add Serial Number to the header
        fputcsv($handle, ["S.No", "Emp ID", "Name", "Email", "Designation", "Reporting Manager", "Phone", "Total Working Days", "Total Present Days", "Total Absent Days", "Total Working Hours"]);
    
        foreach ($allUserAttendance as $row) {
            fputcsv($handle, $row);
        }
    
        fclose($handle);
    
        return Response::make('', 200, $headers);
    }
    


}
