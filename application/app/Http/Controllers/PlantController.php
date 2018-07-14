<?php

namespace App\Http\Controllers;

use App\Plant;
use Illuminate\Http\Request;
use Validator;

class PlantController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission')->except('getList');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = Plant::query();

        $data = [];

        if($request->has('search_item')) {
            $query->where('name', 'LIKE', '%'. trim($request->search_item) . '%')
                ->orWhere('code', 'LIKE', '%'. trim($request->search_item) . '%');

            $data['search_item'] = trim($request->search_item);
        }

        $plants = $query->latest()->paginate();

        $plants->paginationSummary = getPaginationSummary($plants->total(), $plants->perPage(), $plants->currentPage());

        if($data) {
            $plants->appends($data);
        }

        return view('plants.index', compact('plants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = Plant::getCode();

        return view('plants.create', compact('code'));
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
            'name'  => 'required|string|max:255',
            'capacity'  => 'required|numeric|min:0',
            'no_of_generating_unit'  => 'required|numeric|min:1',
            'code_date'  => 'required|date|date_format:Y-m-d',
            'tank_dead_stock'  => 'required|numeric|min:1',
            'energy_meter_multification_factor'  => 'required|numeric|min:1',
            'hfo_storage_tank_number'  => 'required|numeric|min:1',
            'hfo_buffer_tank_number'  => 'required|numeric|min:1',
            'hfo_service_tank_number'  => 'required|numeric|min:1',
            'diesel_tank_number'  => 'required|numeric|min:1',
            'lube_oil_storage_tank_number'  => 'required|numeric|min:1',
            'lube_oil_maintenance_tank_number'  => 'required|numeric|min:1',
            'permissible_outage'  => 'required|numeric|min:1',
        ];

        if(!$request->has('plant_id')) {
            $rules = $rules + [
                'code' => 'required|alpha_num|min:6|max:10|unique:plants',
            ];
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $plant = !$request->has('plant_id') ? new Plant : Plant::findOrFail($request->plant_id);
            
            $plant->name = trim($request->name);

            if(!$request->has('plant_id')) {
                $plant->code = trim($request->code);
            }

            $plant->address = trim($request->address);
            $plant->capacity = trim($request->capacity);
            
            $plant->no_of_generating_unit = trim($request->no_of_generating_unit);
            $plant->code_date = trim($request->code_date);
            $plant->tank_dead_stock = trim($request->tank_dead_stock);
            $plant->energy_meter_multification_factor = trim($request->energy_meter_multification_factor);
            $plant->hfo_storage_tank_number = trim($request->hfo_storage_tank_number);
            $plant->hfo_buffer_tank_number = trim($request->hfo_buffer_tank_number);
            $plant->hfo_service_tank_number = trim($request->hfo_service_tank_number);
            $plant->diesel_tank_number = trim($request->diesel_tank_number);
            $plant->lube_oil_storage_tank_number = trim($request->lube_oil_storage_tank_number);
            $plant->lube_oil_maintenance_tank_number = trim($request->lube_oil_maintenance_tank_number);
            $plant->permissible_outage = trim($request->permissible_outage);
            
            if(!$request->has('plant_id')) {
                $plant->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $plant->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($plant->save()) { 
                $message = toastMessage ( " Plant has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " Plant has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('plants/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Plant  $plant
     * @return \Illuminate\Http\Response
     */
    public function show(Plant $plant)
    {
        return view('plants.show', compact('plant'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Plant  $plant
     * @return \Illuminate\Http\Response
     */
    public function edit(Plant $plant)
    {
        return view('plants.edit', compact('plant'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(Plant::destroy($request->hdnResource)) {
            $message = toastMessage('Plant has been successfully removed.','success');
        }else{
            $message = toastMessage('Plant has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }
}
