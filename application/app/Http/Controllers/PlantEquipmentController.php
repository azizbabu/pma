<?php

namespace App\Http\Controllers;

use App\PlantEquipment;
use Illuminate\Http\Request;
use App\Equipment;
use App\Plant;
use Validator;

class PlantEquipmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission')->except('getList', 'addDummyData');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = PlantEquipment::query();

        $data = [];

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));

            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('search_item')) {
            $query->where('name', 'LIKE', '%'. trim($request->search_item) . '%')
                  ->orWhere('code', 'LIKE', '%'. trim($request->search_item) . '%');

            $data['search_item'] = trim($request->search_item);
        }

        $plantEquipments = $query->latest()->paginate();

        $plantEquipments->paginationSummary = getPaginationSummary($plantEquipments->total(), $plantEquipments->perPage(), $plantEquipments->currentPage());

        if($data) {
            $plantEquipments->appends($data);
        }

        $plants = Plant::getDropDownList();

        return view('plant-equipments.index', compact('plantEquipments', 'plants'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = PlantEquipment::getCode();
        $plants = Plant::getDropDownList();

        return view('plant-equipments.create', compact('code', 'plants'));
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
            'name'      => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $plantEquipment = !$request->has('plant_equipment_id') ? new PlantEquipment : PlantEquipment::findOrFail($request->plant_equipment_id);
            $plantEquipment->plant_id = trim($request->plant_id);
            $plantEquipment->name = trim($request->name);
            
            if(!$request->has('plant_equipment_id')) {
                $code = PlantEquipment::getCode();

                $plantEquipment->code = $code;
                $plantEquipment->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $plantEquipment->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($plantEquipment->save()) { 
                $message = toastMessage ( " Plant equipment has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " Plant equipment has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('plant-equipments/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PlantEquipment  $plantEquipment
     * @return \Illuminate\Http\Response
     */
    public function show(PlantEquipment $plantEquipment)
    {
        return view('plant-equipments.show', compact('plantEquipment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PlantEquipment  $plantEquipment
     * @return \Illuminate\Http\Response
     */
    public function edit(PlantEquipment $plantEquipment)
    {
        $code = $plantEquipment->code;
        $plants = Plant::getDropDownList();

        return view('plant-equipments.edit', compact('plantEquipment', 'code', 'plants'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(PlantEquipment::destroy($request->hdnResource)) {
            $message = toastMessage('Plant equipment has been successfully removed.','success');
        }else{
            $message = toastMessage('Plant equipment has not been removed.','error');
        }

        // redirect
        session()->flash('toast',$message);
        
        return back();
    }

    /**
     * Add dummy data
     *
     * @return \Illuminate\Http\Response
     */
    public function addDummyData()
    {
        $plants = Plant::get(['id', 'name']);

        if($plants->isEmpty()) {
            exit('Plant not found');
        }

        $equipments = Equipment::all();

        if($equipments->isEmpty()) {
            exit('Equipment not found');
        }

        $insert_item_number = 0;

        \DB::table('plant_equipments')->delete();

        foreach($plants as $plant) {
            foreach($equipments as $equipment) {
                $plantEquipment = new PlantEquipment;
                $plantEquipment->plant_id = $plant->id;
                $plantEquipment->equipment_id = $equipment->id;
                $plantEquipment->code = PlantEquipment::getCode();
                $plantEquipment->created_by = 1;    

                if($plantEquipment->save()) { 
                    $insert_item_number++;
                }
            }
        }

        if($insert_item_number) {
            $message = " Plant equipment has been successfully added.";
        }else{
            $message = " Plant equipment has not been successfully added";
        }

        exit($message);
    }

    /**
     * Add dummy data to equipment
     *
     * @return \Illuminate\Http\Response
     */
    public function addDummyEquipments()
    {
        $path = 'equipments.txt';

        $insert_item_number = 0;
        $myfile = fopen($path, "r") or die("Unable to open file!");
        
        while(!feof($myfile)) {
            $equipment = new Equipment;
            $equipment->name = trim(fgets($myfile));   

            if($equipment->save()) { 
                $insert_item_number++;
            }
        }

        fclose($myfile);

        if($insert_item_number) {
            $message = " Equipment has been successfully added.";
        }else{
            $message = " Equipment has not been successfully added";
        }

        exit($message);
    }
}
