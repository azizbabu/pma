<?php

namespace App\Http\Controllers;

use App\EngineGeneration;
use Illuminate\Http\Request;
use App\Engine;
use App\Plant;
use Carbon, DB, Validator;

class EngineGenerationController extends Controller
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
        $query = EngineGeneration::query();

        $data = [];

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));

            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('engine_id')) {
            $query->where('engine_id', trim($request->engine_id));

            $data['engine_id'] = trim($request->engine_id);
        }

        if($request->filled('gen_code')) {
            $query->where('gen_code', 'LIKE', '%'. trim($request->gen_code) . '%');

            $data['gen_code'] = trim($request->gen_code);
        }

        if($request->filled('gen_date')) {
            $query->where('gen_date', trim($request->gen_date));
                
            $data['gen_date'] = trim($request->gen_date);
        }

        $engineGenerations = $query->latest()->paginate();

        $engineGenerations->paginationSummary = getPaginationSummary($engineGenerations->total(), $engineGenerations->perPage(), $engineGenerations->currentPage());

        if($data) {
            $engineGenerations->appends($data);
        }

        $plants = Plant::getDropDownList();
        $engines = Engine::getDropDownList();

        return view('engine-generations.index', compact('engineGenerations', 'plants', 'engines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gen_code = EngineGeneration::getGenCode();
        $plants = Plant::getDropDownList();

        return view('engine-generations.create', compact('gen_code', 'plants'));
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
            'gen_date' => 'required|date|date_format:Y-m-d',
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
            $total_start = count($request->start);
            $total_end = count($request->end);

            if($total_engine_id == $total_start && $total_start == $total_end && $total_end) {
                
                for ($i=0; $i < $total_end; $i++) { 
                    $rules['engine_id.'. $i] = 'required|integer|min:1';
                    $rules['start.'. $i] = 'required|numeric|min:0';
                    $rules['end.'.$i] = 'required|numeric|min:'. (trim($request->start[$i]));
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
                $gen_code = EngineGeneration::getGenCode();

                // Delete data if already exists
                EngineGeneration::wherePlantId(trim($request->plant_id))->whereGenDate(trim($request->gen_date))->forceDelete();

                for ($i=0; $i < $total_end; $i++) { 

                    // insert 
                    $engine = Engine::find($request->engine_id[$i]);
                    if($engine) {
                        $engineGeneration = new EngineGeneration;
                        $engineGeneration->plant_id = trim($request->plant_id);
                        $engineGeneration->gen_date = trim($request->gen_date);
                        $engineGeneration->gen_code = $gen_code;
                        $engineGeneration->engine_id = $request->engine_id[$i];
                        $engineGeneration->start = $request->start[$i];
                        $engineGeneration->end = $request->end[$i];
                        $engineGeneration->total = $request->end[$i] - $request->start[$i];
                        $engineGeneration->created_by = $request->user()->id;

                        if($engineGeneration->save()) {
                            $total_insert_data++;
                        }
                    }
                }
                
                if($total_insert_data) {
                    return response()->json([
                        'status'    => 200,
                        'type'  => 'success',
                        'message'  => 'Engine generation information has been successfully added.',
                    ]);
                }else {
                    return response()->json([
                        'type'  => 'error',
                        'message'  => 'Engine generation information has not been added.',
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
     * @param  \App\EngineGeneration  $engineGeneration
     * @return \Illuminate\Http\Response
     */
    public function show(EngineGeneration $engineGeneration)
    {
        return view('engine-generations.show', compact('engineGeneration'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EngineGeneration  $engineGeneration
     * @return \Illuminate\Http\Response
     */
    public function edit(EngineGeneration $engineGeneration)
    {
        return view('engine-generations.edit', compact('engineGeneration'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EngineGeneration  $engineGeneration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EngineGeneration $engineGeneration)
    {
        $rules = [
            'start'    => 'required|numeric|min:0',
            'end'    => 'required|numeric|min:'.($request->filled('start') ? trim($request->start) : 0),
        ];

        $request->validate($rules);
         
        // update 
        $engineGeneration->start = trim($request->start);
        $engineGeneration->end = trim($request->end);
        $engineGeneration->total = trim($request->end) - trim($request->start);
        $engineGeneration->updated_by = $request->user()->id;

        if($engineGeneration->save()) {
            $message = toastMessage('Engine generation information has been successfully updated');
        }else {
            $message = toastMessage('Engine generation information has been successfully updated', 'error');
        }

        session()->flash('toast', $message);

        return redirect('engine-generations/list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(EngineGeneration::destroy($request->hdnResource)) {
            $message = toastMessage('Engine generation has been successfully removed.','success');
        }else{
            $message = toastMessage('Engine generation has not been removed.','error');
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

        $engineGeneration = EngineGeneration::wherePlantId(trim($request->plant_id))
            ->whereGenDate(trim($request->gen_date))
            ->first(['id']);

        $query = DB::table('engines as e')->leftJoin('engine_generations as eg', 'e.id', '=', 'eg.engine_id')
                ->select(
                    'e.id', 
                    'e.name', 
                    DB::raw('IFNULL(eg.start, 0) as start'),
                    DB::raw('IFNULL(eg.end, 0) as end')
                )
                ->where('e.plant_id', trim($request->plant_id));

        if($engineGeneration) {
            $query->where('eg.gen_date', trim($request->gen_date));
        }

        $engines = $query->get();
        
        return $engines->toJson();
    }
}
