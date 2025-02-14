<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EventName;
use App\Models\EventType;
use App\Models\User;
use App\Models\UserChatCount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AjaxController extends Controller
{
    public function uploadImage(Request $request)
    {
        // dd($request->all());
        if ($request->has('file')) {
            $file = $request->file('file');
            $image = $file->getClientOriginalName();
            $file->move('public/uploads/images/', $image);
            return $image;
        }
        // return response()->json(['message' => "Something went wrong"]);
    }

    public function deleteImage(Request $request)
    {
        // dd($request->all());
        if (File::exists("public/uploads/images/$request->filename")) {
            // File::delete("uploads/products/$request->filename");
            return $request->filename;
        }
    }

    public function storeAttendance(Request $request)
    {
        $request->validate([
            'check_in_full_address' => 'required|string',
            'check_in_latitude' => 'required|string',
            'check_in_longitude' => 'required|string',
            'user_id' => "required"
        ]);

        $userId = $request->user_id;
        $today = Carbon::today()->toDateString();

        // Check if the user has already checked in today
        $existingAttendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();

        if ($existingAttendance) {

            return response()->json([
                'status' => 'error',
                'message' => "You have already checked in today.",
            ], 400);
        }

        // Create new attendance record
        $attendance = new Attendance();
        $attendance->date = $today;
        $attendance->user_id = $userId;
        $attendance->check_in_full_address = $request->check_in_full_address;
        $attendance->check_in_latitude = $request->check_in_latitude;
        $attendance->check_in_longitude = $request->check_in_longitude;
        $attendance->check_in_time = Carbon::now()->format('H:i:s');
        $attendance->status = "Checked In";
        $attendance->save();
        $attendance->check_in_time = date("H:i", strtotime($attendance->check_in_time));
        $attendance->check_out_time = date("H:i", strtotime($attendance->check_out_time));
        return response()->json([
            'status' => 'success',
            'message' => 'Check-in recorded successfully.',
            'data' => $attendance
        ]);
    }

    /**
     * Update Check-out Record
     */
    public function updateAttendance(Request $request)
    {
        $request->validate([
            'check_out_full_address' => 'required|string',
            'check_out_latitude' => 'required|string',
            'check_out_longitude' => 'required|string',
            'user_id' => 'required|string',

        ]);

        $userId = $request->user_id;
        $today = Carbon::today()->toDateString();

        // Find today's check-in record
        $attendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'status' => 'error',
                'message' => "No check-in record found for today.",
            ], 400);
        }

        // if ($attendance->check_out_time) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => "You have already checked out today.",
        //     ], 400);
        // }

        // Update check-out details
        $attendance->check_out_full_address = $request->check_out_full_address;
        $attendance->check_out_latitude = $request->check_out_latitude;
        $attendance->check_out_longitude = $request->check_out_longitude;
        $attendance->check_out_time = Carbon::now()->format('H:i:s');
        $attendance->status = "Checked Out";
        $attendance->save();
        $attendance->check_in_time = date("H:i", strtotime($attendance->check_in_time));
        $attendance->check_out_time = date("H:i", strtotime($attendance->check_out_time));
        return response()->json([
            'status' => 'success',
            'message' => 'Check-out recorded successfully.',
            'data' => $attendance
        ]);
    }

    public function fetchAttendance(Request $request)
    {
        if ($request->has("id")) {
            $id = $request->id;
            $user = User::find($id);
            if ($user) {
                $records = Attendance::where("user_id", $id)->orderBy("id", "desc")->get();
                foreach ($records as $item) {
                    $item->check_in_time = date("H:i", strtotime($item->check_in_time));
                    $item->check_out_time = date("H:i", strtotime($item->check_out_time));
                }
                $today = Attendance::where("user_id", $id)->whereDate("date", now())->orderBy("id", "desc")->first();
                if ($today) {
                    $today->check_in_time = date("H:i", strtotime($today->check_in_time));
                    $today->check_out_time = date("H:i", strtotime($today->check_out_time));
                }
                return response()->json(['success' => true, 'records' => $records, 'today' => $today]);
            }
        }

        return response()->json(['success' => false, 'message' => 'user does not exists']);
    }
}
