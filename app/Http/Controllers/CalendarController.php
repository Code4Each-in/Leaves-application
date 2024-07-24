<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Calendar;

class CalendarController extends Controller
{
    public function index()
    {
       $calendar =  Calendar::all();
        return view('calendar.create_calander', compact('calendar'));
    }

    public function store(Request $request)
    {
      
      $validator = \Validator::make($request->all(), [
         'calendarName' => 'required'
    ]);
            

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $calendar_name = $request->get('calendarName');
        
        $holiday = Calendar::Create([
            'calendar_name' => $calendar_name
         
        ]);

		$request->session()->flash('message','Calender added successfully.');
        return Response()->json(['status'=>200, 'holiday'=>$holiday]);
    }

    public function edit(Request $request)
    {
        $holiday  = Calendar::where(['id' => $request->id])->first();
        return Response()->json(['calendar' =>$holiday]);
    }

    public function update(Request $request)
    {
       // dd($request->all());
        $validator = \Validator::make($request->all(), [
            'calendar_name' => 'required'  
        ]);
 
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
		
        Calendar::where('id', $request->id)
        ->update([
            'calendar_name' => $request->calendar_name
        ]);
		$request->session()->flash('message','Holidays updated successfully.');
        return Response()->json(['status'=>200]);
    }

    public function destroy(Request $request)
    {
        $Calendar = Calendar::where('id',$request->id)->delete();
        $request->session()->flash('message','Calendar deleted successfully.');
       return Response()->json($Calendar );
    }
}
