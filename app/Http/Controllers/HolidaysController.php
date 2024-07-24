<?php



namespace App\Http\Controllers;



use App\Models\Holidays;

use App\Models\Calendar;

use Carbon\Carbon;

use Illuminate\Http\Request;



class HolidaysController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        $holidaysFilter = request()->all() ;

        $allHolidaysFilter = $holidaysFilter['all_holidays'] ?? '';

        if ($allHolidaysFilter == 'on' ) {

            $holidays = Holidays::get();   

        } else {

           // Get the current date

        $currentDate = Carbon::now()->toDateString();



        // Fetch holidays greater than or equal to the current date

        $holidays = Holidays::where('to', '>=', $currentDate)->get();

        }

        return view('holidays.index', compact('holidays','allHolidaysFilter'));

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        //

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {

       // dd($request->all());

        $validator = \Validator::make($request->all(), [

            'holidayName' => 'required', 

            'from' => 'required', 

            'to' => 'required|after_or_equal:from', 

        ],[

            'pageId.required' => 'The page field is required.'

        ]);        



        if ($validator->fails())

        {

            return response()->json(['errors'=>$validator->errors()->all()]);

        }


        // $calendarName = Calendar::where('id', $request->cal_id)->value('calendar_name');
        // dd($calendarName);
        $holidayName = $request->get('holidayName');

        $from = $request->get('from');

        $to = $request->get('to');

        $cal_id = $request->get('cal_id');

        

        $holiday = Holidays::Create([

            'name' => $holidayName,

            'from' => $from,

            'to' => $to,

            'calender_id'  => $cal_id ,

            'created_at' => date('Y-m-d H:i:s'),

            'updated_at' => date('Y-m-d H:i:s'),

        ]);



		$request->session()->flash('message','Holiday added successfully.');

        return Response()->json(['status'=>200, 'holiday'=>$holiday]);

    }

    public function getCalendarNameById (Request $request)
    
    {
        // dd($request);
         $calendarName = Calendar::where('id', $request->id)->value('calendar_name');
        // $id = $request->input('id');

        // Assuming you have a 'calendars' table and a 'Calendar' model
        // $calendar = Calendar::findOrFail($id);

        // Return the name as JSON response
        return response()->json(['name' => $calendarName]);
    }


    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($id)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit(Request $request)

    {

        $holiday  = Holidays::where(['id' => $request->id])->first();

        return Response()->json(['holiday' =>$holiday]);

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request)

    {

        $validator = \Validator::make($request->all(), [

            'edit_holiday_name' => 'required',   

            'edit_from' => 'required',   

            'edit_to' => 'required|after_or_equal:edit_from',   

        ]);

 

        if ($validator->fails())

        {

            return response()->json(['errors'=>$validator->errors()->all()]);

        }

		

        Holidays::where('id', $request->id)

        ->update([

            'name' => $request->edit_holiday_name,

            'from' => $request->edit_from,

            'to' => $request->edit_to,

        ]);

		$request->session()->flash('message','Holidays updated successfully.');

        return Response()->json(['status'=>200]);

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy(Request $request)

    {

        $holiday = Holidays::where('id',$request->id)->delete();

        $request->session()->flash('message','Holiday deleted successfully.');

       return Response()->json($holiday );

    }



    public static function calendar_name($id)

    {

       return $calendar =  Calendar::where('id',$id)->get();

    }



    public function holidays()

    {

        $calender_id =  Request()->segment(2);

        $holidays = Holidays::where('calender_id', $calender_id)->get();

        return view('holidays.all_holidays', compact('holidays'));

    }



  

}

