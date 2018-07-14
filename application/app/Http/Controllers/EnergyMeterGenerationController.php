<?php

namespace App\Http\Controllers;

use App\EnergyMeterGeneration;
use Illuminate\Http\Request;
use App\Meter;
use App\Plant;
use Carbon, DB, Validator;

class EnergyMeterGenerationController extends Controller
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
        $query = EnergyMeterGeneration::query();

        $data = [];

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));

            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('meter_id')) {
            $query->where('meter_id', trim($request->meter_id));

            $data['meter_id'] = trim($request->meter_id);
        }

        if($request->filled('gen_code')) {
            $query->where('gen_code', 'LIKE', '%'. trim($request->gen_code) . '%');

            $data['gen_code'] = trim($request->gen_code);
        }

        if($request->filled('gen_date')) {
            $query->where('gen_date', trim($request->gen_date));
                
            $data['gen_date'] = trim($request->gen_date);
        }

        $energyMeterGenerations = $query->latest()->paginate();

        $energyMeterGenerations->paginationSummary = getPaginationSummary($energyMeterGenerations->total(), $energyMeterGenerations->perPage(), $energyMeterGenerations->currentPage());

        if($data) {
            $energyMeterGenerations->appends($data);
        }

        $plants = Plant::getDropDownList();
        $meters = Meter::getDropDownList();

        return view('energy-meter-generations.index', compact('energyMeterGenerations', 'plants', 'meters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gen_code = EnergyMeterGeneration::getGenCode();
        $plants = Plant::getDropDownList();

        return view('energy-meter-generations.create', compact('gen_code', 'plants'));
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
            
            if(!$request->has('meter_id')) {
                return response([
                    'type'      => 'error',
                    'message'   => 'The meter id is required',
                ]); 
            }

            $total_meter_id = count($request->meter_id);
            $total_export_start = count($request->export_start);
            $total_export_end = count($request->export_end);
            $total_import_start = count($request->import_start);
            $total_import_end = count($request->import_end);

            if($total_meter_id == $total_export_start && $total_export_start == $total_export_end && $total_export_end == $total_import_start && $total_import_start == $total_import_end && $total_import_end) {
                for ($i=0; $i < $total_import_end; $i++) { 
                    $rules['meter_id.'. $i] = 'required|integer|min:1';
                    $rules['export_start.'. $i] = 'required|numeric|min:1';
                    $rules['export_end.'.$i] = 'required|numeric|min:'.(trim($request->export_start[$i]));
                    $rules['import_start.'.$i] = 'required|numeric|min:1';
                    $rules['import_end.'.$i] = 'required|numeric|min:'.(trim($request->import_start[$i])+1);
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
                $gen_code = EnergyMeterGeneration::getGenCode();

                // Delete data if already exists
                EnergyMeterGeneration::wherePlantId(trim($request->plant_id))->whereGenDate(trim($request->gen_date))->forceDelete();

                for ($i=0; $i < $total_import_end; $i++) { 
                    
                    // insert 
                    $meter = Meter::find($request->meter_id[$i]);
                    if($meter) {
                        $energyMeterGeneration = new EnergyMeterGeneration;
                        $energyMeterGeneration->plant_id = trim($request->plant_id);
                        $energyMeterGeneration->gen_date = trim($request->gen_date);
                        $energyMeterGeneration->gen_code = $gen_code;
                        $energyMeterGeneration->meter_id = $request->meter_id[$i];
                        $energyMeterGeneration->export_start = $request->export_start[$i];
                        $energyMeterGeneration->export_end = $request->export_end[$i];
                        $energyMeterGeneration->import_start = $request->import_start[$i];
                        $energyMeterGeneration->import_end = $request->import_end[$i];
                        $energyMeterGeneration->created_by = $request->user()->id;

                        if($energyMeterGeneration->save()) {
                            $total_insert_data++;
                        }
                    }
                }
                
                if($total_insert_data) {
                    return response()->json([
                        'status'    => 200,
                        'type'  => 'success',
                        'message'  => 'Engine meter generation information has been successfully added.',
                    ]);
                }else {
                    return response()->json([
                        'type'  => 'error',
                        'message'  => 'Engine meter generation information has not been added.',
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
     * @param  \App\EnergyMeterGeneration  $energyMeterGeneration
     * @return \Illuminate\Http\Response
     */
    public function show(EnergyMeterGeneration $energyMeterGeneration)
    {
        return view('energy-meter-generations.show', compact('energyMeterGeneration'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EnergyMeterGeneration  $energyMeterGeneration
     * @return \Illuminate\Http\Response
     */
    public function edit(EnergyMeterGeneration $energyMeterGeneration)
    {
        return view('energy-meter-generations.edit', compact('energyMeterGeneration'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EnergyMeterGeneration  $energyMeterGeneration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EnergyMeterGeneration $energyMeterGeneration)
    {
        $rules = [
            'export_start'    => 'required|numeric|min:1',
            'export_end'    => 'required|numeric|min:'.($request->filled('export_start') ? ((double)trim($request->export_start) + 1) : 1),
            'import_start'    => 'required|numeric|min:1',
            'import_end'  => 'required|numeric|min:'.($request->filled('import_start') ? ((double)trim($request->import_start) + 1) : 1),
        ];

        $request->validate($rules);

        // $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     return back()->withInput()->withErrors($validator);
        // }else {
            $energyMeterGeneration->export_start = trim($request->export_start);
            $energyMeterGeneration->export_end = trim($request->export_end);
            $energyMeterGeneration->import_start = trim($request->import_start);
            $energyMeterGeneration->import_end = trim($request->import_end);
            $energyMeterGeneration->updated_by = $request->user()->id;

            if($energyMeterGeneration->save()) {
                $message = toastMessage('Energy meter generation information has been successfully updated');
            }else {
                $message = toastMessage('Energy meter generation information has been successfully updated', 'error');
            }

            session()->flash('toast', $message);

            return redirect('energy-meter-generations/list');
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(EnergyMeterGeneration::destroy($request->hdnResource)) {
            $message = toastMessage('Energy meter generation has been successfully removed.','success');
        }else{
            $message = toastMessage('Energy meter generation has not been removed.','error');
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
    public function getEnergyMeters(Request $request, $plant_id)
    {
        $plant = Plant::find($plant_id);

        if(!$plant) {
            return response()->json([
                'type'      => 'error',
                'message'   => 'Plant not found!',
            ]);
        }

        $energyMeterGeneration = EnergyMeterGeneration::wherePlantId(trim($request->plant_id))
            ->whereGenDate(trim($request->gen_date))
            ->first(['id']);

        $query =  DB::table('meters as m')->leftJoin('energy_meter_generations as emg', 'm.id' , '=', 'emg.meter_id')
                ->select(
                    'm.id', 
                    'm.name',
                    DB::raw('IFNULL(emg.export_start, 0) as export_start'),
                    DB::raw('IFNULL(emg.export_end, 0) as export_end'),
                    DB::raw('IFNULL(emg.import_start, 0) as import_start'),
                    DB::raw('IFNULL(emg.import_end, 0) as import_end')
                )
                ->where('m.plant_id', trim($request->plant_id));

        if($energyMeterGeneration) {
            $query->where('emg.gen_date', trim($request->gen_date));
        }

        $meters = $query->get();

        // $meters = $plant->meters()->get(['id', 'name']);

        // foreach($meters as $meter) {
            
        //     $energyMeterGeneration = $meter->energyGrossGenerations()->whereRoleId(trim($request->role_id))->first([
        //         'start_time', 
        //         'can_update', 
        //         'can_delete', 
        //         'can_update', 
        //         'can_view'
        //     ]);

        //     if($energyMeterGeneration) {
        //         $can_create = $energyMeterGeneration->can_create;
        //         $can_update = $energyMeterGeneration->can_update;
        //         $can_delete = $energyMeterGeneration->can_delete;
        //         $can_view = $energyMeterGeneration->can_view;
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
