<?php

namespace App\Http\Controllers;

use App\EngineGrossGeneration;
use Illuminate\Http\Request;
use App\Engine;
use App\Plant;
use Carbon, DB, Validator;

class EngineGrossGenerationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission')->except('getList', 'getEngines');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = EngineGrossGeneration::query();

        $data = [];

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));

            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('engine_id')) {
            $query->where('engine_id', trim($request->engine_id));

            $data['engine_id'] = trim($request->engine_id);
        }

        if($request->filled('op_code')) {
            $query->where('op_code', 'LIKE', '%'. trim($request->op_code) . '%');

            $data['op_code'] = trim($request->op_code);
        }

        if($request->filled('op_date')) {
            $query->where('op_date', trim($request->op_date));
                
            $data['op_date'] = trim($request->op_date);
        }

        $engineGrossGenerations = $query->latest()->paginate();

        $engineGrossGenerations->paginationSummary = getPaginationSummary($engineGrossGenerations->total(), $engineGrossGenerations->perPage(), $engineGrossGenerations->currentPage());

        if($data) {
            $engineGrossGenerations->appends($data);
        }

        $plants = Plant::getDropDownList();
        $engines = Engine::getDropDownList();

        return view('engine-gross-generations.index', compact('engineGrossGenerations', 'plants', 'engines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $op_code = EngineGrossGeneration::getOpCode();
        $plants = Plant::getDropDownList();

        return view('engine-gross-generations.create', compact('op_code', 'plants'));
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
            'plant_id'  => 'required|integer|min:1',
            'op_date' => 'required|date|date_format:Y-m-d',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    => 400,
                'error'     => $validator->errors(),
            ]);
        }else{
            
            if(!$request->has('engine_id')) {
                return response([
                    'type'      => 'error',
                    'message'   => 'The engine id is required',
                ]); 
            }

            $total_engine_id = count($request->engine_id);
            $total_start_time = count($request->start_time);
            $total_end_time = count($request->end_time);
            $total_diff_time = count($request->diff_time);
            $total_start_op_mwh = count($request->start_op_mwh);
            $total_end_op_mwh = count($request->end_op_mwh);

            if($total_engine_id == $total_start_time && $total_start_time == $total_end_time && $total_end_time == $total_diff_time && $total_diff_time == $total_start_op_mwh && $total_start_op_mwh==$total_end_op_mwh && $total_end_op_mwh) {
                
                for ($i=0; $i < $total_end_op_mwh; $i++) { 
                    $rules['engine_id.'. $i] = 'required|integer|min:1';
                    $rules['start_time.'. $i] = 'required|string';
                    $rules['end_time.'.$i] = 'required|string';
                    $rules['diff_time.'.$i] = 'required|string';
                    $rules['start_op_mwh.'.$i] = 'required|numeric|min:0';
                    $rules['end_op_mwh.'.$i] = 'required|numeric|min:0';
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
                        'message'   => $validation_error,
                    ]);
                }

                $total_insert_data = 0;
                $op_code = EngineGrossGeneration::getOpCode();

                // Delete data if already exists
                // EngineGrossGeneration::wherePlantId(trim($request->plant_id))->whereOpDate(trim($request->op_date))->forceDelete();

                for ($i=0; $i < $total_end_op_mwh; $i++) { 
                    // Get start time
                    $start_time = $request->start_time[$i];
                    $start_time_arr = explode(':', $start_time);

                    if(count($start_time_arr) <= 1) {
                        return response()->json([
                            'type'  => 'error',
                            'message'  => 'The start time '. $i. ' has wrong value',
                        ]);
                    }

                    $start_time_hour = (int)$start_time_arr[0];
                    $start_time_minute = (int)$start_time_arr[1];

                    if(!is_numeric($start_time_hour) || !is_numeric($start_time_minute)) {
                        return response()->json([
                            'type'  => 'error',
                            'message'  => 'The start time '. $i. ' has wrong value',
                        ]);
                    }

                    // Get end time
                    $end_time = $request->end_time[$i];
                    $end_time_arr = explode(':', $end_time);

                    if(count($end_time_arr) <= 1) {
                        return response()->json([
                            'type'  => 'error',
                            'message'  => 'The end time '. $i. ' has wrong value',
                        ]);
                    }

                    $end_time_hour = (int)$end_time_arr[0];
                    $end_time_minute = (int)$end_time_arr[1];

                    if(!is_numeric($end_time_hour) || !is_numeric($end_time_minute)) {
                        return response()->json([
                            'type'  => 'error',
                            'message'  => 'The end time '. $i. ' has wrong value',
                        ]);
                    }

                    if(($end_time_hour < $start_time_hour)) {
                        // dd($start_time_hour , $end_time_hour ,$start_time_minute ,$end_time_minute);
                        return response()->json([
                            'type'  => 'error',
                            'message'  => 'The end time must be grater than start time for engine row '. ($i+1),
                        ]);
                    }

                    if(($start_time_hour == $end_time_hour) && ($start_time_minute > $end_time_minute)) {
                        return response()->json([
                            'type'  => 'error',
                            'message'  => 'The end time must be grater than start time for engine row '. ($i+1),
                        ]);
                    }
                    
                    $min = $end_time_minute-$start_time_minute;
                    $hour_carry = 0;
                    if($min < 0){
                       $min += 60;
                       $hour_carry += 1;
                    }
                    $hour = $end_time_hour - $start_time_hour - $hour_carry;

                    $diff_time = $hour . ":" . sprintf("%02d", $min);

                    // insert 
                    $engine = Engine::find($request->engine_id[$i]);
                    if($engine) {
                        $engineGrossGeneration = new EngineGrossGeneration;
                        $engineGrossGeneration->plant_id = trim($request->plant_id);
                        $engineGrossGeneration->op_date = trim($request->op_date);
                        $engineGrossGeneration->op_code = $op_code;
                        $engineGrossGeneration->engine_id = $request->engine_id[$i];
                        $engineGrossGeneration->start_time = $request->start_time[$i]. ':00';
                        $engineGrossGeneration->end_time = $request->end_time[$i]. ':00';
                        $engineGrossGeneration->diff_time = $diff_time. ':00';
                        $engineGrossGeneration->start_op_mwh = $request->start_op_mwh[$i];
                        $engineGrossGeneration->end_op_mwh = $request->end_op_mwh[$i];
                        $engineGrossGeneration->created_by = $request->user()->id;

                        if($engineGrossGeneration->save()) {
                            $total_insert_data++;
                        }
                    }
                }
                
                if($total_insert_data) {
                    return response()->json([
                        'status'    => 200,
                        'type'  => 'success',
                        'message'  => 'Engine gross generation information has been successfully added.',
                    ]);
                }else {
                    return response()->json([
                        'type'  => 'error',
                        'message'  => 'Engine gross generation information has not been added.',
                    ]);
                }
            }else {
                return response()->json([
                    'type'  => 'error',
                    'message'  => 'Please add engine info correctly',
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EngineGrossGeneration  $engineGrossGeneration
     * @return \Illuminate\Http\Response
     */
    public function show(EngineGrossGeneration $engineGrossGeneration)
    {
        return view('engine-gross-generations.show', compact('engineGrossGeneration'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EngineGrossGeneration  $engineGrossGeneration
     * @return \Illuminate\Http\Response
     */
    public function edit(EngineGrossGeneration $engineGrossGeneration)
    {
        return view('engine-gross-generations.edit', compact('engineGrossGeneration'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EngineGrossGeneration  $engineGrossGeneration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EngineGrossGeneration $engineGrossGeneration)
    {
        $rules = [
            'start_time'    => 'required|string',
            'end_time'    => 'required|string',
            'end_op_mwh'  => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }else {
            $start_time_arr = explode(':', trim($request->start_time));

            if(count($start_time_arr) <= 1) {
                session('toast', toastMessage('The start time has wrong value', 'error'));

                return back()->withInput();
            }

            $start_time_hour = (int)$start_time_arr[0];
            $start_time_minute = (int)$start_time_arr[1];

            if(!is_numeric($start_time_hour) || !is_numeric($start_time_minute)) {
                session('toast', toastMessage('The start time has wrong value', 'error'));

                return back()->withInput();
            }

            // Get end time
            $end_time_arr = explode(':', trim($request->end_time));

            if(count($end_time_arr) <= 1) {
                session('toast', toastMessage('The end time has wrong value', 'error'));

                return back()->withInput();
            }

            $end_time_hour = (int)$end_time_arr[0];
            $end_time_minute = (int)$end_time_arr[1];

            if(!is_numeric($end_time_hour) || !is_numeric($end_time_minute)) {
                session('toast', toastMessage('The end time has wrong value', 'error'));

                return back()->withInput();
            }

            if($end_time_hour < $start_time_hour) {
                session('toast', toastMessage('The end time must be grater than start time', 'error'));

                return back()->withInput();
            }

            if(($start_time_hour == $end_time_hour) && ($start_time_minute > $end_time_minute)) {
                session('toast', toastMessage('The end time must be grater than start time', 'error'));

                return back()->withInput();
            }
            
            $min = $end_time_minute-$start_time_minute;
            $hour_carry = 0;
            if($min < 0){
               $min += 60;
               $hour_carry += 1;
            }
            $hour = $end_time_hour - $start_time_hour - $hour_carry;

            $diff_time = $hour . ":" . sprintf("%02d", $min);

            // update 
            $engineGrossGeneration->start_time = trim($request->start_time). ':00';
            $engineGrossGeneration->end_time = trim($request->end_time). ':00';
            $engineGrossGeneration->diff_time = $diff_time. ':00';
            $engineGrossGeneration->end_op_mwh = trim($request->end_op_mwh);
            $engineGrossGeneration->updated_by = $request->user()->id;

            if($engineGrossGeneration->save()) {
                $message = toastMessage('Engine gross generation information has been successfully updated');
            }else {
                $message = toastMessage('Engine gross generation information has been successfully updated', 'error');
            }

            session()->flash('toast', $message);

            return redirect('engine-gross-generations/list');
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
        if(EngineGrossGeneration::destroy($request->hdnResource)) {
            $message = toastMessage('Engine gross generation has been successfully removed.','success');
        }else{
            $message = toastMessage('Engine gross generation has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }

    /**
     * Get engines based on plant id
     *
     * @param \Illuminate\Http\Request $request.
     * @param int $plant_id
     * @return \Illuminate\Http\Response
     */
    public function getEngines(Request $request, $plant_id)
    {
        $plant = Plant::find($plant_id);

        if(!$plant) {
            return response()->json([
                'type'      => 'error',
                'message'   => 'Plant not found!',
            ]);
        }

        // $engineGrossGeneration = EngineGrossGeneration::wherePlantId($plant_id)->whereOpDate(trim($request->op_date))->first();

        $query = DB::table('engines as e')->leftJoin('engine_gross_generations as egg', 'e.id', '=', 'egg.engine_id')
            ->select(
                'e.id', 
                'e.name', 
                DB::raw('IFNULL(egg.start_time, 0.00) AS start_time'),
                DB::raw('IFNULL(egg.end_time, 0.00) AS end_time'),
                DB::raw('IFNULL(egg.end_op_mwh, 0.00) AS end_op_mwh')
            )->where('e.plant_id', $plant_id);

        // if($engineGrossGeneration) {
        //     $query->select()->where('egg.op_date', trim($request->op_date))->orderBy('egg.op_date');
        // }

        $engines = $query->groupBy('e.id')->get();

        // if($engineGrossGeneration) {
        //     foreach($engines as $engine) {
        //         $engine->end_op_mwh = '0.00';
        //     }
        // }else {
        //     foreach($engines as $engine) {
        //         $engine->start_time = '0.00';
        //         $engine->end_time = '0.00';
        //     }
        // }

        // $engines = $plant->engines()->get(['id', 'name']);

        return $engines->toJson();
    }
}
