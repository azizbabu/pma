<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 500);
use App\EquipmentRunningHour;
use Illuminate\Http\Request;
use App\Plant;
use App\PlantEquipment;
use Carbon, DB, Validator;

class EquipmentRunningHourController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission')->except('getList', 'getPlantEquipments');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = EquipmentRunningHour::query();

        $data = [];

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));

            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('running_year')) {
            $query->where('running_year', trim($request->running_year));

            $data['running_year'] = trim($request->running_year);
        }

        if($request->filled('running_month')) {
            $query->where('running_month', trim($request->running_month));

            $data['running_month'] = trim($request->running_month);
        }

        $equipmentRunningHours = $query->latest()->paginate();

        $equipmentRunningHours->paginationSummary = getPaginationSummary($equipmentRunningHours->total(), $equipmentRunningHours->perPage(), $equipmentRunningHours->currentPage());

        if($data) {
            $equipmentRunningHours->appends($data);
        }

        $plants = Plant::getDropDownList();
        $plantEquipments = PlantEquipment::getDropDownList();

        return view('equipment-running-hours.index', compact('equipmentRunningHours', 'plants', 'plantEquipments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $plants = Plant::getDropDownList();

        return view('equipment-running-hours.create', compact('plants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'running_year'  => 'required|date_format:Y',
            'running_month' => 'required|date_format:m',
            'plant_id'      => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json([
                'status'    => 400,
                'error' => $validator->errors(),
            ]);
        }

        $plant = Plant::find(trim($request->plant_id));

        if(!$plant) {
            return response()->json([
                'type'      => 'error',
                'message'   => 'Plant not found!',
            ]);
        }

        $rules = [
            'plant_equipment_id'    => 'required',
            'start_value'    => 'required',
            'end_value'    => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            $messages = $validator->messages()->all();

            $validation_error = '';

            foreach($messages as $value) {
                $validation_error .= $value . '<br/>';
            }

            return response()->json([
                'type'  => 'error',
                'message' => $validation_error
            ]);
        }

        $total_plant_equipment_id = count($request->plant_equipment_id);
        $total_start_value = count($request->start_value);
        $total_end_value = count($request->end_value);
        $total_insert_data = 0;

        if($total_plant_equipment_id == $total_start_value && $total_start_value == $total_end_value && $total_end_value) {
            
            for ($i=0; $i < $total_end_value; $i++) { 
                $rules['plant_equipment_id.'.$i] = 'required|integer|min:1';
                $rules['start_value.'.$i] = 'required|numeric|min:0';
                $rules['end_value.'.$i] = 'required|numeric|min:'.((float)($request->start_value[$i]));
            }

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()) {
                $messages = $validator->messages()->all();

                $validation_error = '';

                foreach($messages as $value) {
                    $validation_error .= $value . '<br/>';
                }

                return response()->json([
                    'type'  => 'error',
                    'message' => $validation_error
                ]);
            }

            // Delete data if already exists
            // EquipmentRunningHour::wherePlantId(trim($request->plant_id))
            //     ->whereRunningYear(trim($request->running_year))
            //     ->whereRunningMonth(trim($request->running_month))
            //     ->delete();

            for ($i=0; $i < $total_end_value; $i++) { 
                $plantEquipment = PlantEquipment::find(trim($request->plant_equipment_id[$i]));

                if($plantEquipment) {
                    // insert 
                    $equipmentRunningHour = !$request->equipment_running_hour_id[$i] ? new EquipmentRunningHour : EquipmentRunningHour::find($request->equipment_running_hour_id[$i]);
                    $equipmentRunningHour->plant_id = trim($request->plant_id);
                    $equipmentRunningHour->running_year = trim($request->running_year);
                    $equipmentRunningHour->running_month = trim($request->running_month);
                    $equipmentRunningHour->plant_equipment_id = trim($request->plant_equipment_id[$i]);
                    $equipmentRunningHour->start_value = trim($request->start_value[$i]);
                    $equipmentRunningHour->end_value = trim($request->end_value[$i]);
                    $equipmentRunningHour->diff_value = trim($request->end_value[$i]) - trim($request->start_value[$i]);

                    if(!$request->equipment_running_hour_id[$i]) {
                        $equipmentRunningHour->created_by = $request->user()->id;
                        $msg = 'added.';
                    }else {
                        $equipmentRunningHour->updated_by = $request->user()->id;
                        $msg = 'updated.';
                    }

                    if($equipmentRunningHour->save()) {
                        $total_insert_data++;
                    }
                }
            }
        }

        if($total_insert_data) {
            return response()->json([
                'type'  => 'success',
                'message'  => 'Equipment running hour information have been successfully '. $msg,
            ]);
        }else {
            return response()->json([
                'type'      => 'error',
                'message'  => 'Equipment running hour information has not been ' . $msg,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EquipmentRunningHour  $equipmentRunningHour
     * @return \Illuminate\Http\Response
     */
    public function show(EquipmentRunningHour $equipmentRunningHour)
    {
        return view('equipment-running-hours.show', compact('equipmentRunningHour'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EquipmentRunningHour  $equipmentRunningHour
     * @return \Illuminate\Http\Response
     */
    public function edit(EquipmentRunningHour $equipmentRunningHour)
    {
        return view('equipment-running-hours.edit', compact('equipmentRunningHour'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EquipmentRunningHour  $equipmentRunningHour
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EquipmentRunningHour $equipmentRunningHour)
    {
        $rules = [
            'end_value'  => 'required|numeric|min:'.($equipmentRunningHour->start_value + 1),
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }else {
            // update
            $equipmentRunningHour->end_value = trim($request->end_value);
            $equipmentRunningHour->diff_value = trim($request->end_value) - $equipmentRunningHour->start_value;
            $equipmentRunningHour->updated_by = $request->user()->id;

            if($equipmentRunningHour->save())  {
                $message = toastMessage('Equipment running hour has been successfully updated');
            }else {
                $message = toastMessage('Equipment running hour has not been updated', 'error');
            }

            session()->flash('toast', $message);

            return redirect('equipment-running-hours/list');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(EquipmentRunningHour::destroy($request->hdnResource)) {
            $message = toastMessage('Equipment running hour has been successfully removed.','success');
        }else{
            $message = toastMessage('Equipment running hour has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }

    /**
     * Get plant equipment based on plant id
     *
     * @param \Illuminate\Http\Request $request.
     * @return \Illuminate\Http\Response
     */
    public function getPlantEquipments(Request $request)
    {
        $rules = [
            'running_year'  => 'required|date_format:Y',
            'running_month' => 'required|date_format:m',
            'plant_id'      => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json([
                'status'    => 400,
                'error' => $validator->errors(),
            ]);
        }

        $plant = Plant::find(trim($request->plant_id));

        if(!$plant) {
            return response()->json([
                'type'      => 'error',
                'message'   => 'Plant not found!',
            ]);
        }

        // get plant equipments based on running year and running month
        $query = DB::table('plant_equipments as pe')
        ->join('equipment_running_hours as erh', 'pe.id', '=', 'erh.plant_equipment_id')
        ->select(
            'pe.id AS plant_equipment_id', 
            'pe.code', 
            'pe.name',
            'erh.id AS equipment_running_hour_id',
            'erh.start_value',
            'erh.end_value',
            'erh.diff_value'
        )
        ->where('pe.plant_id', $plant->id)
        ->where('erh.running_year', trim($request->running_year))
        ->where('erh.running_month', trim($request->running_month));

        $plantEquipments = $query->groupBy('pe.id')->get();

        if($plantEquipments->isEmpty()) {
            $previous_month_time = Carbon::parse(trim($request->running_year) . '-' . trim($request->running_month))->subMonth();
            $previous_month_year = $previous_month_time->year;
            $previous_month_month = $previous_month_time->month;

            $equipmentRunningHour = EquipmentRunningHour::wherePlantId($plant->id)
                ->whereRunningYear($previous_month_year)
                ->whereRunningMonth($previous_month_month)
                ->first(['id']);

            $query = DB::table('plant_equipments as pe')
            ->leftJoin('equipment_running_hours as erh', 'pe.id', '=', 'erh.plant_equipment_id')
            ->select(
                'pe.id AS plant_equipment_id', 
                'pe.code', 
                'pe.name',
                DB::raw('
                    IFNULL(erh.id, 0) as equipment_running_hour_id,
                    IFNULL(erh.end_value, 0) as start_value
                ')
            )
            ->where('pe.plant_id', $plant->id);

            if($equipmentRunningHour) {
                $query->where('erh.running_month', $previous_month_month)
                    ->where('erh.running_month', $previous_month_month);
            }

            $plantEquipments = $query->groupBy('pe.id')->get();

            if($plantEquipments->isEmpty()) {
                return response()->json([
                    'type'  => 'error',
                    'message'  => 'Plant equipment not found!',
                ]);
            }

            foreach($plantEquipments as $plantEquipment) {
                $plantEquipment->end_value = 0;
                $plantEquipment->diff_value = null;
            }
        }

        return $plantEquipments->toJson();
    }

    /**
     * Add dummy data
     *
     * @return \Illuminate\Http\Response
     */
    public function addDummyData()
    {
        DB::table('equipment_running_hours')->truncate();

        $plantEquipments = PlantEquipment::all();
        $running_year = '2018';

        if($plantEquipments->isEmpty()) {
            return response()->json([
                'message' => 'Data not found!'
            ]);
        }

        $total_insert_data = 0;
        
        // Loop for month number
        for ($j=4; $j <= 6; $j++) { 
            foreach($plantEquipments as $plantEquipment) {
                // get old data
                $equipmentRunningHourOld = EquipmentRunningHour::wherePlantId($plantEquipment->plant_id)->wherePlantEquipmentId($plantEquipment->id)->whereRunningYear($running_year)->whereRunningMonth($j-1)->first();

                $equipmentRunningHour = new EquipmentRunningHour;
                $equipmentRunningHour->plant_id = $plantEquipment->plant_id;
                $equipmentRunningHour->plant_equipment_id = $plantEquipment->id;
                $equipmentRunningHour->running_year = $running_year;
                $equipmentRunningHour->running_month = $j;
                $start_value = $equipmentRunningHourOld ? $equipmentRunningHourOld->end_value : 0;
                $diff_value = mt_rand(0,500);
                $equipmentRunningHour->start_value = $start_value;
                $equipmentRunningHour->end_value = $start_value + $diff_value;
                $equipmentRunningHour->diff_value = $diff_value;
                $equipmentRunningHour->created_by = 1;

                if($equipmentRunningHour->save()) {
                    $total_insert_data++;
                }
            }
        }

        if($total_insert_data) {
            return response()->json([
                'message' => 'Data added'
            ]);
        }

        return response()->json([
            'message' => 'Data not added'
        ]);
    }
}
