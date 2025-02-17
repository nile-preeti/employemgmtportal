<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
                    ->orWhere("phone", "LIKE", "%$keyword%");
            })
            ->where("role_id", 2)
            ->when(request()->filled("status"), function ($query) {
                return $query->where("status", request("status"));
            })
            ->orderBy("id", "desc")
            ->paginate(config("contant.paginatePerPage"));

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
        

        $title = "User Management";

        return view("pages.users.index", compact("title", 'users', 'search'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required| unique:users,email',
            'password' => 'required',
            'designation'=> 'required',
            'phone' => 'required',


        ]);

        $user = new User();
        $user->role_id = 2;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->image = $request->image;
        $user->designation = $request->designation;
        $user->phone = $request->phone;

        $user->status = $request->status;

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['success' => true, 'message' => "User Created Successfully"]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|exists:users,email',
            'designation'=> 'required',
            'phone' => 'required',


        ]);

        $user =  User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->image = $request->image;
        $user->designation = $request->designation;
        $user->phone = $request->phone;
        $user->status = $request->status;

        if ($request->has("password")) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return response()->json(['success' => true, 'message' => "User Updated Successfully"]);
    }

    public function  destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => true, 'message' => "User deleted successfully"]);
        }
        return response()->json(['success' => false, 'message' => "User does not exists"]);
    }
    // user end routes
    public function userAttendance($id)
    {
        $data = Attendance::where('user_id', $id)
            ->when(request()->has("status"), function ($query) {
                return $query->where("status", request("status"));
            })
            ->orderBy("id", "desc")
            ->paginate(10);
        $title = "Employee attendance records";
        return view("pages.users.attendance", compact("data", 'title'));
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
            'email' => 'required|exists:users,email',
        ]);

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();

        // Check if the user exists
        if ($user) {
            // If it's a user (role_id != 1)
            if ($user->role_id != 1) {
                // Attempt to log in as a regular user
                if (Auth::guard('web')->attempt($credentials)) {
                    return response()->json([
                        'status' => 'success',
                        'redirect' => true,
                        'route' => route("user.dashboard"),
                        'message' => "User logged in successfully",
                        'user' => $user
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

        return view("users.attendance_records",compact('user'));
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

        // Count holidays for the current year
        $holidaysCount = Holiday::whereYear('date', $currentYear)->count();

        return view("users.dashboard",compact('holidaysCount'));
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
}
