<?php

namespace App\Http\Controllers;

use App\EnergyGrossGeneration;
use Illuminate\Http\Request;
use App\Meter;
use App\Plant;
use Carbon, Validator;

class EnergyGrossGenerationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission')->except('getList', 'getMeters');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = EnergyGrossGeneration::query();

        $data = [];

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));

            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('meter_id')) {
            $query->where('meter_id', trim($request->meter_id));

            $data['meter_id'] = trim($request->meter_id);
        }

        if($request->filled('op_code')) {
            $query->where('op_code', 'LIKE', '%'. trim($request->op_code) . '%');

            $data['op_code'] = trim($request->op_code);
        }

        if($request->filled('op_date')) {
            $query->where('op_date', trim($request->op_date));
                
            $data['op_date'] = trim($request->op_date);
        }

        $energyGrossGenerations = $query->latest()->paginate();

        $energyGrossGenerations->paginationSummary = getPaginationSummary($energyGrossGenerations->total(), $energyGrossGenerations->perPage(), $energyGrossGenerations->currentPage());

        if($data) {
            $energyGrossGenerations->appends($data);
        }

        $plants = Plant::getDropDownList();
        $meters = Meter::getDropDownList();

        return view('energy-gross-generations.index', compact('energyGrossGenerations', 'plants', 'meters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $op_code = EnergyGrossGeneration::getOpCode();
        $plants = Plant::getDropDownList();

        return view('energy-gross-generations.create', compact('op_code', 'plants'));
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
            
            if(!$request->has('meter_id')) {
                return response([
                    'type'      => 'error',
                    'message'   => 'The meter id is required',
                ]); 
            }

            $total_meter_id = count($request->meter_id);
            $total_export_start_kwh = count($request->export_start_kwh);
            $total_export_end_kwh = count($request->export_end_kwh);
            $total_import_start_kwh = count($request->import_start_kwh);
            $total_import_end_kwh = count($request->import_end_kwh);
            $total_export_start_kvarh = count($request->export_start_kvarh);
            $total_export_end_kvarh = count($request->export_end_kvarh);
            $total_import_start_kvarh = count($request->import_start_kvarh);
            $total_import_end_kvarh = count($request->import_end_kvarh);

            if($total_meter_id == $total_export_start_kwh && $total_export_start_kwh == $total_export_end_kwh && $total_export_end_kwh == $total_import_start_kwh && $total_import_start_kwh == $total_import_end_kwh && $total_import_end_kwh == $total_export_start_kvarh && $total_export_start_kvarh == $total_export_end_kvarh && $total_export_end_kvarh == $total_import_start_kvarh && $total_import_start_kvarh == $total_import_end_kvarh) {
                
                for ($i=0; $i < $total_import_end_kvarh; $i++) { 
                    $rules['meter_id.'. $i] = 'required|integer|min:1';
                    $rules['export_start_kwh.'. $i] = 'required|numeric|min:0';
                    $rules['export_end_kwh.'.$i] = 'required|numeric|min:'.(trim($request->export_start_kwh[$i]));
                    $rules['import_start_kwh.'.$i] = 'required|numeric|min:0';
                    $rules['import_end_kwh.'.$i] = 'required|numeric|min:'.(trim($request->import_start_kwh[$i]));

                    $rules['export_start_kvarh.'. $i] = 'required|numeric|min:0';
                    $rules['export_end_kvarh.'.$i] = 'required|numeric|min:'.(trim($request->export_start_kvarh[$i]));
                    $rules['import_start_kvarh.'.$i] = 'required|numeric|min:0';
                    $rules['import_end_kvarh.'.$i] = 'required|numeric|min:'.(trim($request->import_start_kvarh[$i]));
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
                $op_code = EnergyGrossGeneration::getOpCode();

                // Delete data if already exists
                EnergyGrossGeneration::wherePlantId(trim($request->plant_id))->whereOpDate(trim($request->op_date))->forceDelete();

                for ($i=0; $i < $total_import_end_kwh; $i++) { 
                    
                    // insert 
                    $meter = Meter::find($request->meter_id[$i]);
                    
                    if($meter) {
                        $energyGrossGeneration = new EnergyGrossGeneration;
                        $energyGrossGeneration->plant_id = trim($request->plant_id);
                        $energyGrossGeneration->op_date = trim($request->op_date);
                        $energyGrossGeneration->op_code = $op_code;
                        $energyGrossGeneration->meter_id = $request->meter_id[$i];
                        $energyGrossGeneration->export_start_kwh = $request->export_start_kwh[$i];
                        $energyGrossGeneration->export_end_kwh = $request->export_end_kwh[$i];
                        $energyGrossGeneration->import_start_kwh = $request->import_start_kwh[$i];
                        $energyGrossGeneration->import_end_kwh = $request->import_end_kwh[$i];

                        $energyGrossGeneration->export_start_kvarh = $request->export_start_kvarh[$i];
                        $energyGrossGeneration->export_end_kvarh = $request->export_end_kvarh[$i];
                        $energyGrossGeneration->import_start_kvarh = $request->import_start_kvarh[$i];
                        $energyGrossGeneration->import_end_kvarh = $request->import_end_kvarh[$i];
                        $energyGrossGeneration->created_by = $request->user()->id;

                        if($energyGrossGeneration->save()) {
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
     * @param  \App\EnergyGrossGeneration  $energyGrossGeneration
     * @return \Illuminate\Http\Response
     */
    public function show(EnergyGrossGeneration $energyGrossGeneration)
    {
        return view('energy-gross-generations.show', compact('energyGrossGeneration'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EnergyGrossGeneration  $energyGrossGeneration
     * @return \Illuminate\Http\Response
     */
    public function edit(EnergyGrossGeneration $energyGrossGeneration)
    {
        return view('energy-gross-generations.edit', compact('energyGrossGeneration'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EnergyGrossGeneration  $energyGrossGeneration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EnergyGrossGeneration $energyGrossGeneration)
    {
        $rules = [
            'export_start_kwh'    => 'required|numeric|min:0',
            'export_end_kwh'    => 'required|numeric|min:'.($request->filled('export_start_kwh') ? ((double)trim($request->export_start_kwh)) : 1),
            'import_start_kwh'    => 'required|numeric|min:0',
            'import_end_kwh'  => 'required|numeric|min:'.($request->filled('import_start_kwh') ? ((double)trim($request->import_start_kwh)) : 1),

            'export_start_kvarh'    => 'required|numeric|min:0',
            'export_end_kvarh'    => 'required|numeric|min:'.($request->filled('export_start_kvarh') ? ((double)trim($request->export_start_kvarh)) : 1),
            'import_start_kvarh'    => 'required|numeric|min:0',
            'import_end_kvarh'  => 'required|numeric|min:'.($request->filled('import_start_kvarh') ? ((double)trim($request->import_start_kvarh)) : 1),
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }else {
            $energyGrossGeneration->export_start_kwh = trim($request->export_start_kwh);
            $energyGrossGeneration->export_end_kwh = trim($request->export_end_kwh);
            $energyGrossGeneration->import_start_kwh = trim($request->import_start_kwh);
            $energyGrossGeneration->import_end_kwh = trim($request->import_end_kwh);

            $energyGrossGeneration->export_start_kvarh = trim($request->export_start_kvarh);
            $energyGrossGeneration->export_end_kvarh = trim($request->export_end_kvarh);
            $energyGrossGeneration->import_start_kvarh = trim($request->import_start_kvarh);
            $energyGrossGeneration->import_end_kvarh = trim($request->import_end_kvarh);
            $energyGrossGeneration->updated_by = $request->user()->id;

            if($energyGrossGeneration->save()) {
                $message = toastMessage('Energy gross generation information has been successfully updated');
            }else {
                $message = toastMessage('Energy gross generation information has been successfully updated', 'error');
            }

            session()->flash('toast', $message);

            return redirect('energy-gross-generations/list');
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
        if(EnergyGrossGeneration::destroy($request->hdnResource)) {
            $message = toastMessage('Energy gross generation has been successfully removed.','success');
        }else{
            $message = toastMessage('Energy gross generation has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }

    /**
     * Get meters based on plant id
     *
     * @param \Illuminate\Http\Request $request.
     * @param int $plant_id
     * @return \Illuminate\Http\Response
     */
    public function getMeters(Request $request, $plant_id)
    {
        $plant = Plant::find($plant_id);

        if(!$plant) {
            return response()->json([
                'type'      => 'error',
                'message'   => 'Plant not found!',
            ]);
        }

        $meters = $plant->meters()->get(['id', 'name']);

        // foreach($meters as $meter) {
            
        //     $energyGrossGeneration = $meter->energyGrossGenerations()->whereRoleId(trim($request->role_id))->first([
        //         'start_time', 
        //         'can_update', 
        //         'can_delete', 
        //         'can_update', 
        //         'can_view'
        //     ]);

        //     if($energyGrossGeneration) {
        //         $can_create = $energyGrossGeneration->can_create;
        //         $can_update = $energyGrossGeneration->can_update;
        //         $can_delete = $energyGrossGeneration->can_delete;
        //         $can_view = $energyGrossGeneration->can_view;
        //     }else {
        //         $can_create = false;
        //         $can_update = false;
        //         $can_delete = false;
        //         $can_view   = false;
        //     }

        //     $meter->can_create = $can_create;
        //     $meter->can_update = $can_update;
        //     $meter->can_delete = $can_delete;
        //     $meter->can_view = $can_view;
        // }

        return $meters->toJson();
    }
}
