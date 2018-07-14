<?php

namespace App\Http\Controllers;

use App\DailyPlantGeneration;
use Illuminate\Http\Request;

use App\DailyEnergyMeterBilling;
use App\DailyEngineActivity;
use App\DailyEngineGrossGeneration;
use App\DailyHfoLubeModule;
use App\Engine;
use App\Plant;
use Carbon, DB, Validator;

class DailyPlantGenerationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission')->except('getList', 'getAssociateInfo');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = DailyPlantGeneration::query();

        $data = [];

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));

            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('operation_date')) {
            $query->where('operation_date', trim($request->operation_date));
                
            $data['operation_date'] = trim($request->operation_date);
        }

        $dailyPlantGenerations = $query->latest()->paginate();

        $dailyPlantGenerations->paginationSummary = getPaginationSummary($dailyPlantGenerations->total(), $dailyPlantGenerations->perPage(), $dailyPlantGenerations->currentPage());

        if($data) {
            $dailyPlantGenerations->appends($data);
        }

        $plants = Plant::getDropDownList();

        return view('daily-plant-generations.index', compact('dailyPlantGenerations', 'plants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $plants = Plant::getDropDownList();

        return view('daily-plant-generations.create', compact('plants'));
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
        $rules = [
            'plant_id'  => 'required|integer|min:1',
            'operation_date' => 'required|date|date_format:Y-m-d',
            'plant_load_factor' => 'required|integer|min:0|max:100',
            'plant_fuel_consumption' => 'required|numeric|min:0',
            'total_hfo_stock' => 'required|numeric|min:0',
            'reference_lhv' => 'required|numeric|min:0',
            'aux_boiler_hfo_consumption' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    => 400,
                'error'     => $validator->errors(),
            ]);
        }

        $rules = [
            'daily_engine_gross_generation_id' => 'required',
            'daily_engine_gross_generation_engine_id' => 'required',
            'to_day_gross_generation' => 'required',
            'last_day_gross_generation' => 'required',
            'daily_engine_activity_id' => 'required',
            'daily_engine_activity_engine_id' => 'required',
            'activity_state' => 'required',
            'start_time' => 'required',
            'stop_time' => 'required',
            'daily_energy_meter_billing_id' => 'required',
            'meter_id' => 'required',
            'export_to_day_kwh' => 'required',
            'export_last_day_kwh' => 'required',
            'import_to_day_kwh' => 'required',
            'import_last_day_kwh' => 'required',
            'export_to_day_kvarh' => 'required',
            'export_last_day_kvarh' => 'required',
            'import_to_day_kvarh' => 'required',
            'import_last_day_kvarh' => 'required',
            'daily_hfo_lube_module_id'  => 'required',
            'daily_hfo_lube_module_engine_id'  => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $validation_error = '';

            foreach($messages as $value) {
                $validation_error .= $value . '<br/>';
            }

            return response()->json([
                'type'  => 'error',
                'message'   => $validation_error,
            ]);
        }

        $total_daily_engine_gross_generation_id = count($request->daily_engine_gross_generation_id);
        $total_daily_engine_gross_generation_engine_id = count($request->daily_engine_gross_generation_engine_id);
        $total_to_day_gross_generation = count($request->to_day_gross_generation);
        $total_last_day_gross_generation = count($request->last_day_gross_generation);
        $total_fuel_consumption = count($request->fuel_consumption);

        if($total_daily_engine_gross_generation_id == $total_daily_engine_gross_generation_engine_id && $total_daily_engine_gross_generation_engine_id == $total_to_day_gross_generation && $total_to_day_gross_generation == $total_last_day_gross_generation && $total_last_day_gross_generation == $total_fuel_consumption && $total_fuel_consumption) {
            $is_valid = true;
        }else {
            $is_valid = false;
        }

        $total_daily_engine_activity_id = count($request->daily_engine_activity_id);
        $total_daily_engine_activity_engine_id = count($request->daily_engine_activity_engine_id);
        $total_activity_state = count($request->activity_state);
        $total_start_time = count($request->start_time);
        $total_stop_time = count($request->stop_time);

        if($total_daily_engine_activity_id == $total_daily_engine_activity_engine_id && $total_daily_engine_activity_engine_id == $total_start_time && $total_start_time == $total_stop_time && $total_stop_time) {
            $is_valid = true;
        }else {
            $is_valid = false;
        }

        $total_daily_energy_meter_billing_id = count($request->daily_energy_meter_billing_id);

        $total_daily_hfo_lube_module_id = count($request->daily_hfo_lube_module_id);

        if(!$is_valid) {
            return response()->json([
                'type'  => 'error',
                'message' => 'Please add items correctly'
            ]);
        }

        

        // insert or update daily_plant_generatios table
        $dailyPlantGeneration = $request->filled('daily_plant_generation_id') ? DailyPlantGeneration::find(trim($request->daily_plant_generation_id)) : new DailyPlantGeneration;
        $dailyPlantGeneration->plant_id = trim($request->plant_id);
        $dailyPlantGeneration->operation_date = trim($request->operation_date);
        $dailyPlantGeneration->plant_load_factor = trim($request->plant_load_factor);
        $dailyPlantGeneration->plant_fuel_consumption = trim($request->plant_fuel_consumption);
        $dailyPlantGeneration->total_hfo_stock = trim($request->total_hfo_stock);
        $dailyPlantGeneration->reference_lhv = trim($request->reference_lhv);
        $dailyPlantGeneration->aux_boiler_hfo_consumption = trim($request->aux_boiler_hfo_consumption);
        $dailyPlantGeneration->comments = trim($request->comments);
        $dailyPlantGeneration->remarks = trim($request->remarks);

        if(!$request->filled('daily_plant_generation_id')) {
            $dailyPlantGeneration->created_by = $request->user()->id;
            $msg = 'added.';
        }else {
            $dailyPlantGeneration->updated_by = $request->user()->id;
            $msg = 'updated.';
        }

        if($dailyPlantGeneration->save()) {
            // insert or update daily_gross_generations table
            for ($i=0; $i < $total_daily_engine_gross_generation_id; $i++) { 
                
                $dailyEngineGrossGeneration = $request->daily_engine_gross_generation_id[$i] ? DailyEngineGrossGeneration::find($request->daily_engine_gross_generation_id[$i]): new DailyEngineGrossGeneration;
                $dailyEngineGrossGeneration->daily_plant_generation_id = $dailyPlantGeneration->id;
                $dailyEngineGrossGeneration->plant_id = $dailyPlantGeneration->plant_id;
                $dailyEngineGrossGeneration->operation_date = $dailyPlantGeneration->operation_date;
                $dailyEngineGrossGeneration->engine_id = $request->daily_engine_gross_generation_engine_id[$i];
                $dailyEngineGrossGeneration->last_day_gross_generation = $request->last_day_gross_generation[$i];
                $dailyEngineGrossGeneration->to_day_gross_generation = $request->to_day_gross_generation[$i];
                $dailyEngineGrossGeneration->fuel_consumption = $request->fuel_consumption[$i];

                $dailyEngineGrossGeneration->save();
            }

            for ($i=0; $i < $total_daily_engine_activity_id; $i++) {

                // insert or update daily_engine_activities table
                $dailyEngineActivity = trim($request->daily_engine_activity_id[$i]) ? DailyEngineActivity::find(trim($request->daily_engine_activity_id[$i])): new DailyEngineActivity;
                $dailyEngineActivity->daily_plant_generation_id = $dailyPlantGeneration->id;
                $dailyEngineActivity->plant_id = $dailyPlantGeneration->plant_id;
                $dailyEngineActivity->operation_date = $dailyPlantGeneration->operation_date;
                $dailyEngineActivity->engine_id = trim($request->daily_engine_activity_engine_id[$i]);
                $dailyEngineActivity->activity_state = trim($request->activity_state[$i]);
                $dailyEngineActivity->start_time = trim($request->start_time[$i]).':00';
                $dailyEngineActivity->stop_time = trim($request->stop_time[$i]).':00';
                $dailyEngineActivity->diff_time = getDiffTime(trim($request->start_time[$i]), trim($request->stop_time[$i]));
                $dailyEngineActivity->save();
            }

            for ($i=0; $i < $total_daily_energy_meter_billing_id; $i++) {

                // insert or update daily_energy_meter_billings table
                $dailyEnergyMeterBilling = $request->daily_energy_meter_billing_id[$i] ? DailyEnergyMeterBilling::find($request->daily_energy_meter_billing_id[$i]) : new DailyEnergyMeterBilling;
                $dailyEnergyMeterBilling->daily_plant_generation_id = $dailyPlantGeneration->id;
                $dailyEnergyMeterBilling->plant_id = $dailyPlantGeneration->plant_id;
                $dailyEnergyMeterBilling->operation_date = $dailyPlantGeneration->operation_date;
                $dailyEnergyMeterBilling->meter_id = $request->meter_id[$i];
                $dailyEnergyMeterBilling->export_last_day_kwh = $request->export_last_day_kwh[$i];
                $dailyEnergyMeterBilling->export_to_day_kwh = $request->export_to_day_kwh[$i];
                $dailyEnergyMeterBilling->import_last_day_kwh = $request->import_last_day_kwh[$i];
                $dailyEnergyMeterBilling->import_to_day_kwh = $request->import_to_day_kwh[$i];
                $dailyEnergyMeterBilling->export_last_day_kvarh = $request->export_last_day_kvarh[$i];
                $dailyEnergyMeterBilling->export_to_day_kvarh = $request->export_to_day_kvarh[$i];
                $dailyEnergyMeterBilling->import_last_day_kvarh = $request->import_last_day_kvarh[$i];
                $dailyEnergyMeterBilling->import_to_day_kvarh = $request->import_to_day_kvarh[$i];

                $dailyEnergyMeterBilling->save();
            }

            for ($i=0; $i < $total_daily_hfo_lube_module_id; $i++) { 
                // insert or update daily_hfo_lube_modules table
                $dailyHfoLubeModule = $request->daily_hfo_lube_module_id[$i] ? DailyHfoLubeModule::find($request->daily_hfo_lube_module_id[$i]) : new DailyHfoLubeModule;

                if($dailyHfoLubeModule) {
                    $dailyHfoLubeModule->daily_plant_generation_id = $dailyPlantGeneration->id;
                    $dailyHfoLubeModule->operation_date = $dailyPlantGeneration->operation_date;
                    $dailyHfoLubeModule->plant_id = $dailyPlantGeneration->plant_id;
                    $dailyHfoLubeModule->engine_id = trim($request->daily_hfo_lube_module_engine_id[$i]);
                    $dailyHfoLubeModule->hfo = $request->hfo[$i] ? trim($request->hfo[$i]) : 0;
                    $dailyHfoLubeModule->lube_oil = $request->lube_oil[$i] ? trim($request->lube_oil[$i]): 0;

                    $dailyHfoLubeModule->save();
                }
            }

            return response()->json([
                'type'  => 'success',
                'message'   => 'Daily plant generation info has been successfully '. $msg,
            ]);
        }

        return response()->json([
            'type'  => 'success',
            'message'   => 'Daily plant generation info has not been '. $msg,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DailyPlantGeneration  $dailyPlantGeneration
     * @return \Illuminate\Http\Response
     */
    public function show(DailyPlantGeneration $dailyPlantGeneration)
    {
        $dailyEngineGrossGenerations = $dailyPlantGeneration->dailyEngineGrossGenerations;
        $dailyEngineActivities = $dailyPlantGeneration->dailyEngineActivities;
        $dailyEnergyMeterBillings = $dailyPlantGeneration->dailyEnergyMeterBillings;

        return view('daily-plant-generations.show', compact('dailyPlantGeneration', 'dailyEngineGrossGenerations', 'dailyEngineActivities', 'dailyEnergyMeterBillings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DailyPlantGeneration  $dailyPlantGeneration
     * @return \Illuminate\Http\Response
     */
    public function edit(DailyPlantGeneration $dailyPlantGeneration)
    {
        $plants = Plant::getDropDownList();

        return view('daily-plant-generations.edit', compact('dailyPlantGeneration', 'plants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DailyPlantGeneration  $dailyPlantGeneration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DailyPlantGeneration $dailyPlantGeneration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(DailyPlantGeneration::destroy($request->hdnResource)) {
            $message = toastMessage('Daily plant generation info has been successfully removed.','success');
        }else{
            $message = toastMessage('Daily plant generation info has not been removed.','error');
        }

        // Redirect
        session()->flash('toast', $message);
        
        return back();
    }

    /**
     * Get json info of daily plant generation associate info
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getAssociateInfo(Request $request)
    {
        $plant = Plant::find($request->plant_id);

        if(!$plant) {
            return response()->json([
                'type'  => 'error',
                'message'   => 'Plant not found!'
            ]);
        }

        $dailyPlantGeneration = DailyPlantGeneration::wherePlantId($plant->id)->whereOperationDate(trim($request->operation_date))->first();

        if($dailyPlantGeneration) {
            $dailyEngineGrossGenerations = $dailyPlantGeneration->dailyEngineGrossGenerations;
            $dailyEngineActivities = $dailyPlantGeneration->dailyEngineActivities;

            if($dailyEngineActivities->isNotEmpty()) {
                foreach($dailyEngineActivities as $dailyEngineActivity) {
                    $dailyEngineActivity->engine_name = $dailyEngineActivity->engine->name;
                    $dailyEngineActivity->activity_state_name = config('constants.engine_activity_state.'.$dailyEngineActivity->activity_state);
                    $dailyEngineActivity->start_time = Carbon::parse($dailyEngineActivity->start_time)->format('Y-m-d H:i');
                    $dailyEngineActivity->stop_time = Carbon::parse($dailyEngineActivity->stop_time)->format('Y-m-d H:i');
                }
            }
        }else {
            $dailyPlantGeneration = '';
            $dailyEngineActivities = '';
        }
            
        $dailyEngineGrossGenerationLastDay = DailyEngineGrossGeneration::wherePlantId($plant->id)
            ->where('operation_date', '<', trim($request->operation_date))
            ->pluck('to_day_gross_generation', 'engine_id')->all();

        $query = DB::table('engines AS e')->leftJoin('daily_engine_gross_generations as degg', 'e.id', '=', 'degg.engine_id')
            ->select(
                'e.id',
                'e.name',
                DB::raw('IFNULL(degg.id, 0) AS daily_engine_gross_generation_id'),
                DB::raw('IFNULL(degg.last_day_gross_generation, 0) AS last_day_gross_generation'),
                DB::raw('IFNULL(degg.to_day_gross_generation, 0) AS to_day_gross_generation'),
                DB::raw('IFNULL(degg.fuel_consumption, 0) AS fuel_consumption')
            )->where('e.plant_id', $plant->id);

        if($dailyPlantGeneration) {
            $query->where('degg.daily_plant_generation_id', $dailyPlantGeneration->id);
        }

        $dailyEngineGrossGenerations = $query->groupBy('e.id')->get();

        if($dailyEngineGrossGenerations->isEmpty()) {
            return response()->json([
                'type'  => 'error',
                'message'   => 'Daily engine gross generation info not found!'
            ]);
        }

        if(!$dailyPlantGeneration && $dailyEngineGrossGenerationLastDay) {
            foreach($dailyEngineGrossGenerations as $dailyEngineGrossGeneration) {
                $dailyEngineGrossGeneration->daily_engine_gross_generation_id = 0;
                if(array_key_exists($dailyEngineGrossGeneration->id, $dailyEngineGrossGenerationLastDay)) {
                    $dailyEngineGrossGeneration->last_day_gross_generation = $dailyEngineGrossGenerationLastDay[$dailyEngineGrossGeneration->id];
                }
                $dailyEngineGrossGeneration->to_day_gross_generation = 0;
                $dailyEngineGrossGeneration->fuel_consumption = 0;
            }
        }

        $query = DB::table('meters AS m')
            ->leftJoin('daily_energy_meter_billings AS demb', 'm.id', '=', 'demb.meter_id')
            ->select(
                'm.id',
                'm.name',
                DB::raw('IFNULL(demb.id, 0) AS daily_energy_meter_billing_id'),
                DB::raw('IFNULL(demb.export_last_day_kwh, 0) AS export_last_day_kwh'),
                DB::raw('IFNULL(demb.export_to_day_kwh, 0) AS export_to_day_kwh'),
                DB::raw('IFNULL(demb.import_last_day_kwh, 0) AS import_last_day_kwh'),
                DB::raw('IFNULL(demb.import_to_day_kwh, 0) AS import_to_day_kwh'),
                DB::raw('IFNULL(demb.export_last_day_kvarh, 0) AS export_last_day_kvarh'),
                DB::raw('IFNULL(demb.export_to_day_kvarh, 0) AS export_to_day_kvarh'),
                DB::raw('IFNULL(demb.import_last_day_kvarh, 0) AS import_last_day_kvarh'),
                DB::raw('IFNULL(demb.import_to_day_kvarh, 0) AS import_to_day_kvarh')
            )->where('m.plant_id', $plant->id);

        if($dailyPlantGeneration) {
            $query->where('demb.daily_plant_generation_id', $dailyPlantGeneration->id);
        }

        $dailyEnergyMeterBillings = $query->groupBy('m.id')->get();

        if($dailyEnergyMeterBillings->isEmpty()) {
            return response()->json([
                'type'  => 'error',
                'message'  => 'Daily energy billing info not found!',
            ]);
        }

        // Get last day value for daily energy meter billings
        $dailyEnergyMeterBillingsLastDay = DB::table('meters AS m')
            ->leftJoin('daily_energy_meter_billings AS demb', 'm.id', '=', 'demb.meter_id')
            ->select(
                'm.id',
                'm.name',
                DB::raw('IFNULL(demb.export_to_day_kwh, 0) AS export_last_day_kwh'),
                DB::raw('IFNULL(demb.import_to_day_kwh, 0) AS import_last_day_kwh'),
                DB::raw('IFNULL(demb.export_to_day_kvarh, 0) AS export_last_day_kvarh'),
                DB::raw('IFNULL(demb.import_to_day_kvarh, 0) AS import_last_day_kvarh')
            )->where('m.plant_id', $plant->id)
            ->where('demb.operation_date', '<', trim($request->operation_date))
            ->groupBy('m.id')
            ->get();

        $dailyEnergyMeterBillingsLastDayArr = [];
        
        if($dailyEnergyMeterBillingsLastDay->isNotEmpty()) {
            foreach($dailyEnergyMeterBillingsLastDay as $dailyEnergyMeterBillingsLastDayItem) {
                $dailyEnergyMeterBillingsLastDayArr['export_last_day_kwh'][$dailyEnergyMeterBillingsLastDayItem->id] = $dailyEnergyMeterBillingsLastDayItem->export_last_day_kwh;
                $dailyEnergyMeterBillingsLastDayArr['import_last_day_kwh'][$dailyEnergyMeterBillingsLastDayItem->id] = $dailyEnergyMeterBillingsLastDayItem->import_last_day_kwh;
                $dailyEnergyMeterBillingsLastDayArr['export_last_day_kvarh'][$dailyEnergyMeterBillingsLastDayItem->id] = $dailyEnergyMeterBillingsLastDayItem->export_last_day_kvarh;
                $dailyEnergyMeterBillingsLastDayArr['import_last_day_kvarh'][$dailyEnergyMeterBillingsLastDayItem->id] = $dailyEnergyMeterBillingsLastDayItem->import_last_day_kvarh;
            }
        }

        if(!$dailyPlantGeneration && $dailyEnergyMeterBillingsLastDayArr) {
            foreach($dailyEnergyMeterBillings as $dailyEnergyMeterBilling) {
                $dailyEnergyMeterBilling->daily_energy_meter_billing_id = 0;
                $dailyEnergyMeterBilling->export_to_day_kwh =  0;
                $dailyEnergyMeterBilling->export_last_day_kwh =  $dailyEnergyMeterBillingsLastDayArr['export_last_day_kwh'][$dailyEnergyMeterBilling->id];
                $dailyEnergyMeterBilling->import_to_day_kwh =  0;
                $dailyEnergyMeterBilling->import_last_day_kwh =  $dailyEnergyMeterBillingsLastDayArr['import_last_day_kwh'][$dailyEnergyMeterBilling->id];
                $dailyEnergyMeterBilling->export_to_day_kvarh =  0;
                $dailyEnergyMeterBilling->export_last_day_kvarh =  $dailyEnergyMeterBillingsLastDayArr['export_last_day_kvarh'][$dailyEnergyMeterBilling->id];
                $dailyEnergyMeterBilling->import_to_day_kvarh =  0;
                $dailyEnergyMeterBilling->import_last_day_kvarh =  $dailyEnergyMeterBillingsLastDayArr['import_last_day_kvarh'][$dailyEnergyMeterBilling->id];
            }
        }

        $engines = Engine::getDropDownList(true, $plant->id);

        if($engines) {
            $engineOptions = '';
            
            foreach($engines as $key=>$value) {
                $engineOptions .= '<option value="'.$key.'">'.$value.'</option>';
            }
        }

        $query = DB::table('engines AS e')
                    ->leftJoin('daily_hfo_lube_modules AS dhlm', 'e.id', '=', 'dhlm.engine_id')
                    ->select(
                        'e.id AS engine_id',
                        'e.name',
                        DB::raw('
                            IFNULL(dhlm.id, 0) AS daily_hfo_lube_module_id,
                            IFNULL(dhlm.hfo, 0) AS hfo,
                            IFNULL(dhlm.lube_oil, 0) AS lube_oil
                        ')
                    )->where('e.plant_id', $plant->id);

        if($dailyPlantGeneration) {
            $query->where('dhlm.daily_plant_generation_id', $dailyPlantGeneration->id);
        }

        $dailyHfoLubeModules = $query->get();

        if(!$dailyPlantGeneration) {
            foreach($dailyHfoLubeModules as $dailyHfoLubeModule) {
                $dailyHfoLubeModule->daily_hfo_lube_module_id = 0;
                $dailyHfoLubeModule->hfo = 0;
                $dailyHfoLubeModule->lube_oil = 0;
            }
        }

        $arr = [
            'dailyPlantGeneration' => $dailyPlantGeneration,
            'dailyEngineActivities' => $dailyEngineActivities,
            'dailyEngineGrossGenerations' => $dailyEngineGrossGenerations,
            'engineOptions'   => $engineOptions,
            'dailyEnergyMeterBillings' => $dailyEnergyMeterBillings,
            'dailyHfoLubeModules' => $dailyHfoLubeModules
        ];

        return json_encode($arr);
    }
}
