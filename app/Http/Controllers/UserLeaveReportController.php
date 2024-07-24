<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UserLeaveReport;
use App\Models\UserLeaves;

class UserLeaveReportController extends Controller
{
    public function leaveReport($id)
    {
        // Retrieve the leave data for a specific user_id and the current year
        $currentYear = date('Y'); 
        $totalLeaveData = UserLeaveReport::where('user_id', $id)
            ->where('year', '=', $currentYear)
            ->first();

        //check if $totalLeaveData is not null... if its null then assign 0
        $totalLeaves = $totalLeaveData ? $totalLeaveData->total_leaves : 0;


        //Retrieve all approved leave data for a specific user in the current year
        $totalLeaveSpent = UserLeaves::where('leave_status', 'approved')
            ->whereYear('from', date('Y'))
            ->where('user_id', $id)
            ->get();
        //gets the count of approvedleaves
        $totalLeaveSpentCount = $totalLeaveSpent->count(); 
        

        //get pending leaves count 
        $pendingLeavesCount = $totalLeaves - $totalLeaveSpentCount;


        //
        $userLeaves = UserLeaves::where('user_leaves.user_id', $id)->orderBy('id', 'desc')->get(['user_leaves.*']);
        // dd($userLeaves);
            // $currentDate = date('Y-m-d'); //current date
           
            // $showLeaves = UserLeaves::join('users', 'user_leaves.user_id', '=', 'users.id')->whereDate('from', '<=', $currentDate)->whereDate('to', '>=', $currentDate)->where('leave_status', '=', 'approved')->get();
            // dd($showLeaves);
            // if (!empty($showLeaves)) {

            //     $leaveStatus = UserLeaves::join('users', 'user_leaves.status_change_by', '=', 'users.id')
            //         ->where('user_leaves.user_id', $id)
            //         ->select('user_leaves.leave_status', 'user_leaves.id as leave_id', 'user_leaves.updated_at', 'users.first_name', 'users.last_name',)
            //         ->get();
    
            // }
            // dd($userLeaves);
            return view('leaves.leavereport', [
                    'totalLeaveData' => $totalLeaves,
                    'totalLeaveSpentCount' => $totalLeaveSpentCount,
                    'pendingLeavesCount' => $pendingLeavesCount,
                    'userLeaves' => $userLeaves
            ]);
    }

    public function add_user_holidays(Request $request)
	{
		$validator = \Validator::make($request->all(), [
            'holidays' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
    
        $currentYear = date('Y');
        $userTotalLeaves = UserLeaveReport::updateOrCreate(
            ['user_id' => $request->user_id, 'year' => $currentYear],
            [
                'total_leaves' => $request->holidays,
            ]
        );
    
        return response()->json(['status' => 200]);
	}
}
