<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CoastalVesselCarring;
use App\CoastalVesselReceiving;
use App\Plant;
use Carbon, Validator;

class CoastalVesselReceivingController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $coastal_vessel_carring_id
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request, $coastal_vessel_carring_id)
    {
    	$coastalVesselCarring = CoastalVesselCarring::findOrFail($coastal_vessel_carring_id);
        $query = CoastalVesselReceiving::whereCoastalVesselCarringId($coastal_vessel_carring_id);

        $data = [];

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));
                
            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('cvr_number')) {
            $query->where('cvr_number', 'LIKE', '%'. trim($request->cvr_number) . '%');

            $data['cvr_number'] = trim($request->cvr_number);
        }

        $coastalVesselReceivings = $query->latest()->paginate();

        if($coastalVesselReceivings->isEmpty()) {
            return redirect('coastal-vessel-receivings/create/'.$coastal_vessel_carring_id);
        }

        $coastalVesselReceivings->paginationSummary = getPaginationSummary($coastalVesselReceivings->total(), $coastalVesselReceivings->perPage(), $coastalVesselReceivings->currentPage());

        if($data) {
            $coastalVesselReceivings->appends($data);
        }

        $plants = Plant::getDropDownList();

        return view('coastal-vessel-receivings.index', compact('coastalVesselCarring', 'coastalVesselReceivings', 'plants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $coastal_vessel_carring_id
     * @return \Illuminate\Http\Response
     */
    public function create($coastal_vessel_carring_id)
    {
    	$coastalVesselCarring = CoastalVesselCarring::findOrFail($coastal_vessel_carring_id);
    	
        $cvr_number = CoastalVesselReceiving::getCvrNumber($coastalVesselCarring);
        $plants = Plant::getDropDownList();

        return view('coastal-vessel-receivings.create', compact('coastalVesselCarring', 'cvr_number', 'plants'));
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
            'cvr_date'  => 'required|date|date_format:Y-m-d',
            'cvr_qty'  => 'required|numeric|min:1',
            'load_qty'  => 'required|numeric|min:1|max:'.($request->filled('cvr_qty') ? trim($request->cvr_qty) : 1),
            'plant_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
        	$coastalVesselCarring = CoastalVesselCarring::findOrFail($request->coastal_vessel_carring_id);
        	$cvr_number = CoastalVesselReceiving::getCvrNumber($coastalVesselCarring);
        	if($coastalVesselCarring->code !== substr($cvr_number, 0, 10)) {
        		session()->flash('toast', toastMessage('The coastal vessel carring is not valid', 'error'));

        		return redirect()->back()->withErrors($validator)->withInput();
        	}

            // store or update
            $coastalVesselReceiving = !$request->has('coastal_vessel_receiving_id') ? new CoastalVesselReceiving : CoastalVesselReceiving::findOrFail($request->coastal_vessel_receiving_id);
            $coastalVesselReceiving->coastal_vessel_carring_id = trim($request->coastal_vessel_carring_id);

            if(!$request->has('coastal_vessel_receiving_id')) {
                $coastalVesselReceiving->cvr_number = $cvr_number;
                $load_qty_old = 0;
            }else {
                $load_qty_old = $coastalVesselReceiving->load_qty;
            }

            $coastalVesselReceiving->cvr_date = trim($request->cvr_date);
            $coastalVesselReceiving->cvr_qty = trim($request->cvr_qty);
            $coastalVesselReceiving->load_qty = trim($request->load_qty);
            $coastalVesselReceiving->loss_qty = trim($request->cvr_qty) - trim($request->load_qty);
            $coastalVesselReceiving->loss = round(((trim($request->cvr_qty) - trim($request->load_qty))/trim($request->cvr_qty) * 100), 2);
            $coastalVesselReceiving->lighter_vessel_name = trim($request->lighter_vessel_name);
            $coastalVesselReceiving->plant_id = trim($request->plant_id);
            $coastalVesselReceiving->plant_receive_date = $request->filled('plant_receive_date') ? trim($request->plant_receive_date) : null;
            
            if(!$request->has('coastal_vessel_receiving_id')) {
                $coastalVesselReceiving->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $coastalVesselReceiving->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($coastalVesselReceiving->save()) { 
                $coastalVesselCarring = CoastalVesselCarring::findOrFail($request->coastal_vessel_carring_id);
                $total_load_qty = $coastalVesselCarring->getTotalLoadQty();
            	
                // update receive qty and waiting qty of coatal vessel carring
            	$coastalVesselCarring->received_quantity = $total_load_qty;
            	$coastalVesselCarring->transport_loss = ($coastalVesselCarring->invoice_quantity - $total_load_qty)/$coastalVesselCarring->invoice_quantity * 100;
                $coastalVesselCarring->waiting_quantity = $coastalVesselCarring->invoice_quantity - $coastalVesselCarring->CoastalVesselReceivings()->sum('cvr_qty');

            	$coastalVesselCarring->save();

                // update plant stock
                $plant = Plant::find(trim($request->plant_id));
                $plant->stock = $plant->stock - $load_qty_old + $coastalVesselReceiving->load_qty;
                $plant->save();

                $message = toastMessage ( " Coastal vessel receiving has been successfully $msg", 'success' );
            }else{
                $message = toastMessage ( " Coastal vessel receiving has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('coastal-vessel-receivings/list/'.$coastalVesselCarring->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CoastalVesselReceiving  $coastalVesselReceiving
     * @return \Illuminate\Http\Response
     */
    public function show(CoastalVesselReceiving $coastalVesselReceiving)
    {
        return view('coastal-vessel-receivings.show', compact('coastalVesselReceiving'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CoastalVesselReceiving  $coastalVesselReceiving
     * @return \Illuminate\Http\Response
     */
    public function edit(CoastalVesselReceiving $coastalVesselReceiving)
    {
        $cvr_number = $coastalVesselReceiving->cvr_number;
        $plants = Plant::getDropDownList();
        $coastalVesselCarring = $coastalVesselReceiving->coastalVesselCarring;

        return view('coastal-vessel-receivings.edit', compact('coastalVesselReceiving', 'cvr_number', 'plants', 'coastalVesselCarring'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(CoastalVesselReceiving::destroy($request->hdnResource)) {
            $message = toastMessage('Coastal vessel receiving has been successfully removed.','success');
        }else{
            $message = toastMessage('Coastal vessel receiving has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }
}
