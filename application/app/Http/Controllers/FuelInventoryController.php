<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FuelInventory;
use App\FuelType;
use App\Plant;

class FuelInventoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission')->except('getList', 'getFuelTypeUnit');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = FuelInventory::query();

        $data = [];

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));

            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('fuel_type_id')) {
            $query->where('fuel_type_id', trim($request->fuel_type_id));

            $data['fuel_type_id'] = trim($request->fuel_type_id);
        }

        if($request->filled('transaction_date')) {
            $query->where('transaction_date', trim($request->transaction_date));
                
            $data['transaction_date'] = trim($request->transaction_date);
        }

        $fuelInventories = $query->latest()->paginate();

        $fuelInventories->paginationSummary = getPaginationSummary($fuelInventories->total(), $fuelInventories->perPage(), $fuelInventories->currentPage());

        if($data) {
            $fuelInventories->appends($data);
        }

        $plants = Plant::getDropDownList();
        $fuelTypes = FuelType::getDropDownList(true, 1);

        return view('fuel-inventories.index', compact('fuelInventories', 'plants', 'fuelTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $transaction_code = FuelInventory::getTransactionCode();
        $plants = Plant::getDropDownList();
        $fuelTypes = FuelType::getDropDownList(true, 1);
        $opening_stock = 0;

        return view('fuel-inventories.create', compact('transaction_code', 'plants', 'fuelTypes', 'opening_stock'));
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
            'transaction_date'  => 'required|date|date_format:Y-m-d',
            'fuel_type_id'  => 'required|integer|min:1',
        ];
  
        $available_stock = ($request->opening_stock ? trim($request->opening_stock) : 0) + ($request->received_quantity ? trim($request->received_quantity) : 0);

        if(!$available_stock) {
            $rules = $rules + [
                'invoice_quantity'  => 'required|numeric|min:1',
                'received_quantity'  => 'required|numeric|min:1',
            ];
        }

        $request->validate($rules);

        // insert or update
        $fuelInventory = !$request->has('fuel_inventory_id') ? new FuelInventory : FuelInventory::findOrFail($request->fuel_inventory_id);
        $fuelInventory->plant_id = trim($request->plant_id);
        $fuelInventory->fuel_type_id = trim($request->fuel_type_id);

        if(!$request->has('fuel_inventory_id')) {
            $transaction_code = FuelInventory::getTransactionCode();
            $fuelInventory->transaction_code = $transaction_code;
        }

        $fuelInventory->transaction_date = trim($request->transaction_date);
        $fuelInventory->invoice_quantity = trim($request->invoice_quantity);
        $fuelInventory->received_quantity = trim($request->received_quantity);
        if(trim($request->invoice_quantity) && trim($request->received_quantity)) {
            $fuelInventory->transportation_loss = (trim($request->received_quantity) - trim($request->invoice_quantity))/trim($request->received_quantity);
        }else {
            $fuelInventory->transportation_loss = 0;
        }
        $fuelInventory->available_stock = FuelInventory::getOpeningStock(trim($request->plant_id), trim($request->fuel_type_id), trim($request->transaction_date)) + trim($request->received_quantity);
        $fuelInventory->consumption = trim($request->consumption);
        
        if(!$request->has('fuel_inventory_id')) {
            $fuelInventory->created_by = $request->user()->id;
            $msg = 'added';
        }else {
            $fuelInventory->updated_by = $request->user()->id;
            $msg = 'updated';
        }

        if($fuelInventory->save()) { 
            $message = toastMessage ( " Fuel inventory has been successfully $msg", 'success' );

        }else{
            $message = toastMessage ( " Fuel inventory has not been successfully $msg", 'error' );
        }

        // redirect
        session()->flash('toast', $message);
        
        return redirect('fuel-inventories/list');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FuelInventory $fuelInventory
     * @return \Illuminate\Http\Response
     */
    public function show(FuelInventory $fuelInventory)
    {
        return view('fuel-inventories.show', compact('fuelInventory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FuelInventory $fuelInventory
     * @return \Illuminate\Http\Response
     */
    public function edit(FuelInventory $fuelInventory)
    {
        $transaction_code = $fuelInventory->transaction_code;
        $plants = Plant::getDropDownList();
        $fuelTypes = FuelType::getDropDownList(true, 1);
        $opening_stock = FuelInventory::getOpeningStock($fuelInventory->plant_id, $fuelInventory->fuel_type_id, $fuelInventory->transaction_date);

        return view('fuel-inventories.edit', compact('fuelInventory', 'transaction_code', 'plants', 'fuelTypes', 'opening_stock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        if(FuelInventory::destroy($request->hdnResource)) {
            $message = toastMessage('Fuel inventory info has been successfully removed.','success');
        }else{
            $message = toastMessage('Fuel inventory info has not been removed.','error');
        }

        // Redirect
        session()->flash('toast', $message);
        
        return back();
    }

    /**
     * Get unit of fuel type
     *
     * @param  \Illuminate\Http\Request $request
     * @param int $fuel_type_id
     * @return \Illuminate\Http\Response
     */
    public function getFuelTypeUnit(Request $request, $fuel_type_id)
    {
        $fuelType = FuelType::findOrFail($fuel_type_id);

        return $fuelType->unit;
    }

     /**
     * Get opening stock
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $date
     * @return \Illuminate\Http\Response
     */
    public function getOpeningStock(Request $request, $date)
    {
        return FuelInventory::getOpeningStock(trim($request->plant_id), trim($request->fuel_type_id), $date);
    }
}
