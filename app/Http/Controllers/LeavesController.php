<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserLeaves;

use Carbon\Carbon;

use App\Http\Requests\StoreUserLeavesRequest;

use App\Mail\LeaveRequestMail;

use App\Mail\LeaveStatusMail;

use Illuminate\Support\Facades\Input;

use App\Models\Roles;

use App\Models\Managers;

use App\Models\LeaveType;

use App\Models\Holidays;

use Illuminate\Support\Facades\Mail;

use App\Mail\NotifyMail;

use App\Models\Users;

use App\Notifications\EmailNotification;

use Illuminate\Support\Facades\Auth;







class LeavesController extends Controller

{

    public function index()

    {

        $leavesData = UserLeaves::orderBy('id','desc')->where('user_id',auth()->user()->id)->get();

		$roleData=Roles::where(['id' => auth()->user()->role_id])->first();

        $leave_type = LeaveType::all();

        return view('leaves.index',compact('leavesData', 'roleData', 'leave_type'));  



    }  

    public function store(StoreUserLeavesRequest $request)

    {

        // dd($request);

        if (isset($request->validator) && $request->validator->fails()) {

            return response()->json(['errors'=>$request->validator->errors()->all()]);

        } 

        $string = $request->type;

        $parts = explode("_", $string);

        $type = '';

        foreach ($parts as $part) {

            $type .= ucfirst($part) . ' ';

        }

        $type = trim($type);  

        

        if (isset($request->half_day)) {

            $string = $request->half_day;

            $parts = explode("_", $string);

            $halfday = "";

            foreach ($parts as $part) {

                $halfday .= ucfirst($part) . ' ';

            }

            $halfday = trim($halfday);

        } else {

            $halfday = NULL; // Set to null if 'half_day' is not present in the request

        }

        $from = Carbon::parse($request->from);

        $to = Carbon::parse($request->to);

      // Check for overlapping leave periods with existing leaves
        $existingLeave = UserLeaves::where('user_id', auth()->user()->id)
            ->where(function ($query) use ($from, $to) {
                $query->where(function ($q) use ($from, $to) {
                    $q->where('from', '<=', $from)->where('to', '>=', $from);
                })->orWhere(function ($q) use ($from, $to) {
                    $q->where('from', '<=', $to)->where('to', '>=', $to);
                })->orWhere(function ($q) use ($from, $to) {
                    $q->where('from', '>=', $from)->where('to', '<=', $to);
                });
            })
            ->first();

        // If overlapping leave exists, return error response
        if ($existingLeave) {
            return response()->json(['errors' => ['Leave period overlaps with an existing leave.']]);
        }

        $calander_id = Auth::user()->calander_id;

       $holidays =  Holidays::where('calender_id', $calander_id)->get();

       $total = [];

       foreach($holidays as $row){

        $holiday_from = Carbon::parse($row->from);

        $holiday_to = Carbon::parse($row->to);

    

        $daysWithinHoliday = 0;

    

       

        $daysInRange = $from->diffInDays($to)+1;

         

        for ($i = 0; $i < $daysInRange; $i++) {

            $currentDate = $from->copy()->addDays($i);

    

          //  if ($currentDate->between($holiday_from, $holiday_to)) {

        if ($currentDate->between($holiday_from, $holiday_to) && !$currentDate->isWeekend()) {

                $daysWithinHoliday++;

            }

        }

        if ($daysWithinHoliday > 0) {

             $total[] = $daysWithinHoliday;

           // echo "The date range {$from->toDateString()} to {$to->toDateString()} has {$daysWithinHoliday} day(s) within the holiday range {$holiday_from->toDateString()} to {$holiday_to->toDateString()}.\n";

        } else {

          //  echo "The date range {$from->toDateString()} to {$to->toDateString()} does not fall within the holiday range {$holiday_from->toDateString()} to {$holiday_to->toDateString()}.\n";

        }

       }

   

    

    



        $sumTotal = array_sum($total);

     

       if($sumTotal>0){

          $total_days =  $request->total_days - $sumTotal;

       }

      else{

        $total_days =  $request->total_days;

       }

    //    dump($request->total_days);

    //    dump($sumTotal);

    //    dump($total_days);

       

     

    

      // dump($total_days);

       

        //  dump($total_days);



        $userLeaves=UserLeaves::create([     

            'user_id'=> auth()->user()->id,     

            'from'=>$request->from,

            'to'=>$request->to,

            'type'=> $type,

            'half_day' => $halfday,

            'leave_day_count' => $total_days,

            'notes'=>$request->notes,

           ]);    



           $userObj = UserLeaves::join('users', 'user_leaves.user_id', '=', 'users.id')

           ->where('user_leaves.id', '=',  $userLeaves->id)

           ->select(['user_leaves.*', 'users.email', 'users.first_name', 'users.last_name', 'users.role_id'])

           ->first();

           $data = $userObj;

           $subject = "Leave Application - ".ucfirst($userObj->first_name)." ".ucfirst($userObj->last_name);

           $data->subject = $subject;

           if($userLeaves){

           $id = $userLeaves->user_id;

           $user = Users::find($id);

           $role_id = $user->role_id;

           $roles =Roles::select('*')->where('id', '=',$role_id)->first();

           if($roles->name == "Employee")

           {

    		    $managersData = Users::join('managers', 'users.id', '=', 'managers.parent_user_id')->where('managers.user_id',auth()->user()->id)->where('status',1)->get([ 'managers.user_id','users.email']);

                // Gets the Only Emails of managers Related to user with pluck method

                $managerEmails = $managersData->pluck('email');

                $rolesData = Users::join('roles', 'users.role_id', '=', 'roles.id')

                ->select('users.id', 'users.email')

                ->where('status',1)

                ->whereIn('roles.name', ['Super Admin', 'HR Manager'])

                ->get();

                $roleEmails = $rolesData->pluck('email');

                // Merging mail collection data in one collection 

                $emails = $managerEmails->merge($roleEmails);

                Mail::to($emails)->send(new LeaveRequestMail($data));

                

           }elseif ($roles->name == "Manager") {

            

            $managersData = Users::join('managers', 'users.id', '=', 'managers.parent_user_id')->where('managers.user_id',auth()->user()->id)->where('status',1)->get([ 'managers.user_id','users.email']);

            // Gets the Only Emails of managers Related to user with pluck method

            $managerEmails = $managersData->pluck('email');

            $rolesData = Users::join('roles', 'users.role_id', '=', 'roles.id')

            ->select('users.id', 'users.email')

            ->where('status',1)

            ->whereIn('roles.name', ['Super Admin', 'HR Manager'])

            ->get();

            $roleEmails = $rolesData->pluck('email');

            // Merging mail collection data in one collection 

          //  $emails = $managerEmails->merge($roleEmails);

          //  Mail::to($emails)->send(new LeaveRequestMail($data));

            

           }elseif ($roles->name == "HR Manager") {



            $managersData = Users::join('managers', 'users.id', '=', 'managers.parent_user_id')->where('managers.user_id',auth()->user()->id)->where('status',1)->get([ 'managers.user_id','users.email']);

            // Gets the Only Emails of managers Related to user with pluck method

            $managerEmails = $managersData->pluck('email');

            $rolesData = Users::join('roles', 'users.role_id', '=', 'roles.id')

            ->select('users.id', 'users.email')

            ->where('status',1)

            ->whereIn('roles.name', ['Super Admin'])

            ->get();

            $roleEmails = $rolesData->pluck('email');

            // Merging mail collection data in one collection 

          //  $emails = $managerEmails->merge($roleEmails);

          //  Mail::to($emails)->send(new LeaveRequestMail($data));

    

           }

           $request->session()->flash('message','Leaves added successfully.');

        }



           return Response()->json(['status'=>200, 'leaves'=>$userLeaves]);

    }

    public function setLeavesApproved(Request $request)

	 {

        

        $userLeaves = UserLeaves::where(['id'=>$request->LeavesId])

			->update([

            'leave_status' =>$request->LeavesStatus,

            'status_change_by'=> auth()->user()->id,

          

			 ]);

             $userObj = UserLeaves::join('users', 'users.id', '=', 'user_leaves.user_id')

                        ->where('user_leaves.id', $request->LeavesId)

                        ->select('users.email','users.first_name','users.last_name','user_leaves.type','user_leaves.from' ,'user_leaves.notes','user_leaves.to','user_leaves.leave_status','user_leaves.half_day' ,'user_leaves.leave_day_count')->first();

            $data = $userObj;

            $subject = "Leave Request - ".ucfirst($userObj->leave_status);

            $data->subject = $subject;

            $userEmail = $userObj->email;

             if($userObj->leave_status != 'requested' && $userLeaves > 0){

                Mail::to($userEmail)->send(new LeaveStatusMail($data));

             }



			 $request->session()->flash('message', 'user leave status updated' );

		     return Response()->json(['status'=>200]);	

	 }

     

     public function showTeamData()

	 {

        // $members = Users::whereHas('role', function ($q) {
        //         $q->where('name', '!=', 'Super Admin');
        //         })->where('status',1)
        //         ->get();
        $currentUserId = auth()->user()->id;

        if (auth()->user()->role->name == 'Super Admin') {
            $members = Users::where('id', '!=', $currentUserId)
                            ->where('status', 1)
                            ->get();
        } else {
            $members = Users::join('managers', 'users.id', '=', 'managers.user_id')
                            ->where('users.status', 1)
                            ->where('managers.parent_user_id', $currentUserId)
                            ->select('users.*')
                            ->get();
        }

        if (auth()->user()->role->name == 'Super Admin')

		{
            $leave_type = LeaveType::all();
         
            $teamLeaves= UserLeaves::join('users', 'user_leaves.user_id', '=', 'users.id')->orderBy('id','desc')->get(['user_leaves.*','users.first_name']);

        }elseif (auth()->user()->role->name == 'HR Manager') {
       
            $teamLeaves= UserLeaves::join('users', 'user_leaves.user_id', '=', 'users.id')->where('user_leaves.user_id','!=', auth()->user()->id)->orderBy('id','desc')->get(['user_leaves.*','users.first_name']);

            

        }

         else

         {
            $leave_type = LeaveType::all();
            $teamLeaves = UserLeaves::join('managers', 'user_leaves.user_id', '=', 'managers.user_id')->join('users', 'user_leaves.user_id', '=', 'users.id')->where('managers.parent_user_id',auth()->user()->id)->get(['user_leaves.*', 'managers.user_id','users.first_name']);

         }

         if (!empty($teamLeaves)){
            $leave_type = LeaveType::all();
            $leaveStatus = UserLeaves::join('users', 'user_leaves.status_change_by', '=', 'users.id')

        ->select('user_leaves.leave_status','user_leaves.id as leave_id','user_leaves.updated_at', 'users.first_name', 'users.last_name', )

        ->get();

         }



        return view('leaves.team',compact('teamLeaves','leaveStatus','members', 'leave_type'));

	 }





     public function addTeamLeaves(Request $request)
     {
        // dd($request->member_id);
        $validator = \Validator::make($request->all(), [

            'from' => 'required|date',

            'to' => 'required|date',

            'half_day' => 'nullable', 

            'total_days' => 'required',

            'from' => 'required',       

            'type' => 'required',

            'notes' => 'nullable',

            'member_id' => 'required',       

        ]);

 

        if ($validator->fails())

        {

            return response()->json(['errors'=>$validator->errors()->all()]);

        }

        if (isset($request->half_day)) {

            $string = $request->half_day;

            $parts = explode("_", $string);

            $halfday = "";

            foreach ($parts as $part) {

                $halfday .= ucfirst($part) . ' ';

            }

            $halfday = trim($halfday);

        } else {

            $halfday = NULL; // Set to null if 'half_day' is not present in the request

        }

        $string = $request->type;

        $parts = explode("_", $string);

        $type = '';

        foreach ($parts as $part) {

            $type .= ucfirst($part) . ' ';

        }

        $type = trim($type); 

        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        // Check for existing leaves overlapping with the given period
        $existingLeave = UserLeaves::where('user_id', $request->member_id)
            ->where(function ($query) use ($from, $to) {
                $query->where(function ($q) use ($from, $to) {
                    $q->where('from', '<=', $from)->where('to', '>=', $from);
                })->orWhere(function ($q) use ($from, $to) {
                    $q->where('from', '<=', $to)->where('to', '>=', $to);
                })->orWhere(function ($q) use ($from, $to) {
                    $q->where('from', '>=', $from)->where('to', '<=', $to);
                });
            })
            ->first();

        if ($existingLeave) {
            return response()->json(['errors' => ['Leave period overlaps with an existing leave.']]);
        }
        
        $get_cal_ids = Users::where('id', $request->member_id)->value('calander_id');
      
        $calander_id = $get_cal_ids;
       
        $holidays =  Holidays::where('calender_id', $calander_id)->get();
      
        $total = [];
 
        foreach($holidays as $row){
 
         $holiday_from = Carbon::parse($row->from);
         
 
         $holiday_to = Carbon::parse($row->to);
         
         $daysWithinHoliday = 0;
         $daysInRange = $from->diffInDays($to)+1;
 
         for ($i = 0; $i < $daysInRange; $i++) {
 
             $currentDate = $from->copy()->addDays($i);
 
           //  if ($currentDate->between($holiday_from, $holiday_to)) {
 
         if ($currentDate->between($holiday_from, $holiday_to) && !$currentDate->isWeekend()) {
 
                 $daysWithinHoliday++;
 
             }
 
         }
 
         if ($daysWithinHoliday > 0) {
 
              $total[] = $daysWithinHoliday;
 
             //echo "The date range {$from->toDateString()} to {$to->toDateString()} has {$daysWithinHoliday} day(s) within the holiday range {$holiday_from->toDateString()} to {$holiday_to->toDateString()}.\n";
 
         } else {
 
           //  echo "The date range {$from->toDateString()} to {$to->toDateString()} does not fall within the holiday range {$holiday_from->toDateString()} to {$holiday_to->toDateString()}.\n";
 
         }
 
        }
 
         $sumTotal = array_sum($total);
        
      
 
        if($sumTotal>0){
      
           $total_days =  $request->total_days - $sumTotal;
        
        }
 
       else{
 
         $total_days =  $request->total_days;
 
        }

        $teamLeave = UserLeaves::create([     

            'user_id'=> $request->member_id,     

            'from'=>$request->from,

            'to'=>$request->to,

            'type'=> $type,

            'half_day' => $halfday,

            'leave_day_count' => $total_days,

            'notes'=>$request->notes,

           ]);  

        $leave_id = $teamLeave->id;

        $leavesDetail = UserLeaves::find($leave_id);

           if($teamLeave){

            $userLeavesStatus = UserLeaves::where(['id'=>$leave_id])

			->update([

            'leave_status' => 'approved',

            'status_change_by'=> auth()->user()->id,

          

			 ]);

            $messages["subject"] = "Your Leave Is Added #$leavesDetail->id for $leavesDetail->from to $leavesDetail->to";
            $messages["title"] = "Your Leave has been added from $leavesDetail->from to $leavesDetail->to for $leavesDetail->leave_day_count days and reason is $leavesDetail->notes .";
            $messages["body-text"] = "If a leave is added incorrectly or for any other queries, please contact HR";
            $user = Users::find($leavesDetail->user_id);

           $user->notify(new EmailNotification($messages));

       }	

	    

        $request->session()->flash('message', 'Team Leave Added Successfully.' );

        return Response()->json(['status'=>200]);	

     }



     public function leave_type()

     {

        return view('leaves.create_leave_type');

     }

     public function store_leave_type(Request $request)

     {

        $validated = $request->validate([

            'leave_type' => 'required',
            'total_holidays' => 'required'

        ]);

        $data = array(

            "leave_type" => $request->leave_type,
            "leave_count" => $request->total_holidays

        );

       $create_leave_type =  LeaveType::create($data);

       if($create_leave_type){

        return redirect()->route('all_leave_type.index')->with('message', 'Leave Type Created successfully!');

       }





     }



     public function all_leave_type()

     {

        $leaves = LeaveType::all();

        return view('leaves.all_leave_type', compact('leaves'));

     }



     public function edit_leave_type(Request $req)

     {

          $leave_type_id =  $req->id;

          $leave_data = LeaveType::select('leave_type', 'leave_count') // Select only 'leave_type' and 'leave_count'
                            ->where('id', $leave_type_id)
                            ->first();

          return json_encode($leave_data);

     }



     public function update_leave_type(Request $request)

     {

        

        $validator = \Validator::make($request->all(), [

            'leave_type' => 'required',
            'total_holidays' => 'required'
        ]);

 

        if ($validator->fails())

        {

            return response()->json(['errors'=>$validator->errors()->all()]);

        }

		$data = array(

           "leave_type" =>  $request->leave_type,
           "leave_count" => $request->total_holidays
        );

        LeaveType::where('id', $request->leave_type_id) ->update($data);

		$request->session()->flash('message','Leave Type updated successfully.');

        return Response()->json(['status'=>200]);

     }



     public function delete_leave_type(Request $req)

     {

        $id = $req->id;

         $delete = LeaveType::where('id',$id)->delete();

         if($delete){

            $req->session()->flash('message','Leave Type Deleted successfully.');

             return Response()->json(['status'=>200]);

         }

     }



     public static function type_name($id)

     {

      return   $type_name = LeaveType::where('id', $id)->get();

     }



} 