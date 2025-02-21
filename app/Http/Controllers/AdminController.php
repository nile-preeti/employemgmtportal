<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;
use App\Models\Holiday;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $users = User::where("role_id", 2)->orderBy('emp_id','DESC')->get();
        $totalHolidays = Holiday::whereYear('date', Carbon::now()->year)->count();
       
        
    
        return view("pages.dashboard", compact("users" ,"totalHolidays"));
    }


    public function downloadLogs()
    {
        // Fetch users where status = 1 and role_id = 2
        $users = User::where('status', 1)->where('role_id', 2)->orderby('id','DESC')->get();

        // Define CSV headers
        $csvHeader = ['S.No.','Emp ID', 'Name', 'Email', 'Phone', 'Designation'];
        
        // Convert users data to CSV format
        $csvData = [];
        $serialNo = 1;
        foreach ($users as $user) {
            $csvData[] = [
                $serialNo++,
                $user->emp_id ?? 'N/A',
                $user->name ?? 'N/A',
                $user->email ?? 'N/A' ,
                $user->phone ?? 'N/A',
                $user->designation ?? 'N/A',
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
        'email.exists' => "This email is not registered with AVOT."
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
       
        return view("pages.users.details", compact("title", 'user', ));
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
   
}
