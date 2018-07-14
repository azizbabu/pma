<?php

namespace App\Http\Controllers;

use App\CoastalVesselCarring;
use Illuminate\Http\Request;
use App\CoastalVessel;
use App\Plant;
use App\Terminal;
use App\Tank;
use Carbon, Validator;

class CoastalVesselCarringController extends Controller
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
        $query = CoastalVesselCarring::query();

        $data = [];

        if($request->filled('coastal_vessel_id')) {
            $query->where('coastal_vessel_id', trim($request->coastal_vessel_id));
                
            $data['coastal_vessel_id'] = trim($request->coastal_vessel_id);
        }

        if($request->filled('tank_id')) {
            $query->where('tank_id', trim($request->tank_id));
                
            $data['tank_id'] = trim($request->tank_id);
        }

        if($request->filled('search_item')) {
            $query->where('code', 'LIKE', '%'. trim($request->search_item) . '%');

            $data['search_item'] = trim($request->search_item);
        }

        $coastalVesselCarrings = $query->latest()->paginate();

        $coastalVesselCarrings->paginationSummary = getPaginationSummary($coastalVesselCarrings->total(), $coastalVesselCarrings->perPage(), $coastalVesselCarrings->currentPage());

        if($data) {
            $coastalVesselCarrings->appends($data);
        }

        $coastalVessels = CoastalVessel::getDropDownList();
        $tanks = Tank::getDropDownList();

        return view('coastal-vessel-carrings.index', compact('coastalVesselCarrings', 'coastalVessels', 'tanks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = CoastalVesselCarring::getCode();
        $coastalVessels = CoastalVessel::getDropDownList();
        $plants = Plant::getDropDownList();
        $tanks = Tank::getDropDownList();

        return view('coastal-vessel-carrings.create', compact('code', 'coastalVessels', 'plants', 'tanks'));
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
            'coastal_vessel_id'  => 'required|integer',
            'tank_id'  => 'required|integer',
            'carring_date'  => 'required|date|date_format:Y-m-d',
            'loading_date'  => 'required|date|date_format:Y-m-d',
            'received_date'  => 'required|date|date_format:Y-m-d',
            'invoice_quantity'  => 'required|numeric|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            // dd($request->all());
            $coastalVesselCarring = !$request->has('coastal_vessel_carring_id') ? new CoastalVesselCarring : CoastalVesselCarring::findOrFail($request->coastal_vessel_carring_id);
            
            $coastalVesselCarring->coastal_vessel_id = trim($request->coastal_vessel_id);
            $coastalVesselCarring->plant_id = trim($request->plant_id);
            $coastalVesselCarring->tank_id = trim($request->tank_id);

            if(!$request->has('coastal_vessel_carring_id')) {
                $code = CoastalVesselCarring::getCode();
                $coastalVesselCarring->code = $code;
                $invoice_qty_old = 0;
            }else {
                $invoice_qty_old = $invoice_qty_old = 0;
            }

            $coastalVesselCarring->carring_date = trim($request->carring_date);
            $coastalVesselCarring->loading_date = trim($request->loading_date);
            $coastalVesselCarring->received_date = trim($request->received_date);
            $coastalVesselCarring->invoice_quantity = trim($request->invoice_quantity);
            $coastalVesselCarring->waiting_quantity = trim($request->invoice_quantity);
            $coastalVesselCarring->comment = trim($request->comment);
            
            if(!$request->has('coastal_vessel_carring_id')) {
                $coastalVesselCarring->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $coastalVesselCarring->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($coastalVesselCarring->save()) { 

                // update terminal stock
                $terminal = Terminal::find(1);
                $terminal->stock = $terminal->stock + $invoice_qty_old - $coastalVesselCarring->invoice_quantity;
                $terminal->save();

                $message = toastMessage ( " Coastal vessel carring has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " Coastal vessel carring has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('coastal-vessel-carrings/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CoastalVesselCarring  $coastalVesselCarring
     * @return \Illuminate\Http\Response
     */
    public function show(CoastalVesselCarring $coastalVesselCarring)
    {
        return view('coastal-vessel-carrings.show', compact('coastalVesselCarring'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CoastalVesselCarring  $coastalVesselCarring
     * @return \Illuminate\Http\Response
     */
    public function edit(CoastalVesselCarring $coastalVesselCarring)
    {
        $code = $coastalVesselCarring->code;
        $coastalVessels = CoastalVessel::getDropDownList();
        $plants = Plant::getDropDownList();
        $tanks = Tank::getDropDownList();

        return view('coastal-vessel-carrings.edit', compact('coastalVesselCarring', 'code', 'coastalVessels', 'plants', 'tanks'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(CoastalVesselCarring::destroy($request->hdnResource)) {
            $message = toastMessage('Coastal vessel carring has been successfully removed.','success');
        }else{
            $message = toastMessage('Coastal vessel carring has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }
}
