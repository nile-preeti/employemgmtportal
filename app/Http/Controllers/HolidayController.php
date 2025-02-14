<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $data = Holiday::when($request->filled("search"), function ($query) use ($request) {
                $keyword = trim($request->search);
                return $query->where("reason", "LIKE", "%$keyword%")
                             ->orWhere("date", "LIKE", "%$keyword%");
            })
            ->orderByRaw("MONTH(date) ASC, DAY(date) ASC") // Order by month first, then day
            ->paginate(10);
    
        $title = "Holiday Management";
    
        return view("pages.holidays.list", compact("title", 'data'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'reason' => 'required',
            'date' => 'required|',

        ]);

        $holiday = new Holiday();
        $holiday->reason = $request->reason;
        $holiday->date = $request->date;
        $holiday->day = Carbon::parse($request->date)->format('l'); 
        $holiday->save();

        return response()->json(['success' => true, 'message' => "Holiday Added Successfully"]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required',
            'date' => 'required|',
            
        ]);

        $holiday =  Holiday::find($id);
        $holiday->reason = $request->reason;
        $holiday->date = $request->date;
        $holiday->day = Carbon::parse($request->date)->format('l'); 

        $holiday->save();
        return response()->json(['success' => true, 'message' => "Holiday Updated Successfully"]);
    }

    public function  destroy($id)
    {
        $holiday = Holiday::find($id);
        if ($holiday) {
            $holiday->delete();
            return response()->json(['success' => true, 'message' => "Holiday deleted successfully"]);
        }
        return response()->json(['success' => false, 'message' => "holiday does not exists"]);
    }
}
