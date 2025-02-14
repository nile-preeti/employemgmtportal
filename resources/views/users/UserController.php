<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->search;
        $users = User::when(request()->filled("search"), function ($query) {
            $keyword = trim(request("search"));
            return $query->where("name", "LIKE", "%$keyword%")->orWhere("designation", "LIKE", "%$keyword%")->orWhere("email", "LIKE", "%$keyword%")->orWhere("phone", "LIKE", "%$keyword%");
        })
            ->where("role_id", 2)
            ->when(request()->filled("status"), function ($query) {
                return $query->where("status", request("status"));
            })

            ->orderBy("id", "desc")->paginate(config("contant.paginatePerPage"));

        $title = "User Management";

        return view("pages.users.index", compact("title", 'users','search'));
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

        $request->validate([
            'password' => 'required',
            'email' => 'required|exists:users,email',
        ]);
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return response()->json([
                'status' => 'success',
                'redirect' => true,
                'route' => route("user.dashboard"),
                'user' => $user,
                'message' => "User logged in successfully"
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
        ]);
    }

    public function attendance_records()
    {

        return view("users.attendance_records");
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

        return view("users.dashboard");
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['success' => true]);
    }
}
