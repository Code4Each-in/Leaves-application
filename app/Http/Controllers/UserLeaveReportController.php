<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UserLeaveReport;
use App\Models\UserLeaves;
use App\Models\LeaveType;

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
        $userLeaves = UserLeaves::join('users', 'user_leaves.user_id', '=', 'users.id')->where('user_leaves.user_id', $id)->orderBy('id', 'desc')->get(['user_leaves.*', 'users.first_name']);
        
            $currentDate = date('Y-m-d'); //current date
           
            $showLeaves = UserLeaves::join('users', 'user_leaves.user_id', '=', 'users.id')->whereDate('from', '<=', $currentDate)->whereDate('to', '>=', $currentDate)->where('leave_status', '=', 'approved')->get();
            // dd($showLeaves);
            if (!empty($showLeaves)) {

                $leaveStatus = UserLeaves::join('users', 'user_leaves.status_change_by', '=', 'users.id')
                    ->where('user_leaves.user_id', $id)
                    ->where('user_leaves.leave_status', 'approved') 
                    ->select('user_leaves.leave_status', 'user_leaves.id as leave_id', 'user_leaves.updated_at', 'users.first_name', 'users.last_name',)
                    ->get();
    
            }
            // dd($leaveStatus);

            $currentYear = now()->year;
            // Fetch all leave types
            $leaveTypes = LeaveType::all();
            
            // Fetch user leaves for the current year
            $total_leave_data = LeaveType::join('user_leaves', function($join) use ($id, $currentYear) {
                $join->on('leave_types.id', '=', 'user_leaves.type')
                    ->where('user_leaves.user_id', '=', $id)
                    ->whereYear('user_leaves.created_at', $currentYear)
                    ->where('user_leaves.leave_status', '=', 'approved');
            })
            ->select('leave_types.leave_count', 'leave_types.id as leave_type_id', 'leave_types.leave_type', 'user_leaves.leave_day_count')
            ->get();
            
            // Use Laravel collection to group by 'leave_type_id'
            $grouped = $total_leave_data->groupBy('leave_type_id');
            
            // Initialize an array to store the final result
            $segregatedArrays = [];
            
            // Iterate through each leave type
            foreach ($leaveTypes as $leaveType) {
                // Initialize variables
                $totalLeaveDayCount = 0;
            
                // Check if there are user leaves for this leave type
                if ($grouped->has($leaveType->id)) {
                    // Sum up the leave_day_count for this leave type
                    $totalLeaveDayCount = $grouped[$leaveType->id]->sum('leave_day_count');
                }
            
                // Calculate pending leaves
                    $pendingLeaves = $leaveType->leave_count - $totalLeaveDayCount;
            
                // Create the array structure as desired
                $segregatedArrays[] = [
                    'leave_count' => $leaveType->leave_count ?? 0, // Set default to 0 if leave_count is null
                    'leave_type_id' => $leaveType->id,
                    'leave_type' => $leaveType->leave_type,
                    'leave_day_count' => $totalLeaveDayCount,
                    'pending_leaves' => $pendingLeaves, // Ensure pending leaves is not negative
                ];
            }        
            return view('leaves.leavereport', [
                    'totalLeaveData' => $totalLeaves,
                    'totalLeaveSpentCount' => $totalLeaveSpentCount,
                    'pendingLeavesCount' => $pendingLeavesCount,
                    'userLeaves' => $userLeaves,
                    'segregatedArrays' => $segregatedArrays,
                    'leaveStatus' => $leaveStatus,
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
