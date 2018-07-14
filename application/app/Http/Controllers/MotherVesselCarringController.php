<?php

namespace App\Http\Controllers;

use App\MotherVesselCarring;
use Illuminate\Http\Request;
use App\MotherVessel;
use App\Terminal;
use Carbon, Validator;

class MotherVesselCarringController extends Controller
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
        $query = MotherVesselCarring::query();

        $data = [];

        if($request->filled('mother_vessel_id')) {
            $query->where('mother_vessel_id', trim($request->mother_vessel_id));
                
            $data['mother_vessel_id'] = trim($request->mother_vessel_id);
        }

        if($request->filled('search_item')) {
            $query->where('code', 'LIKE', '%'. trim($request->search_item) . '%')
                ->orWhere('lc_number', 'LIKE', '%'. trim($request->search_item) . '%');

            $data['search_item'] = trim($request->search_item);
        }

        $motherVesselCarrings = $query->latest()->paginate();

        $motherVesselCarrings->paginationSummary = getPaginationSummary($motherVesselCarrings->total(), $motherVesselCarrings->perPage(), $motherVesselCarrings->currentPage());

        if($data) {
            $motherVesselCarrings->appends($data);
        }

        $motherVessels = MotherVessel::getDropDownList();

        return view('mother-vessel-carrings.index', compact('motherVesselCarrings', 'motherVessels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = MotherVesselCarring::getCode();
        $lcNumber = MotherVesselCarring::getLcNumber();
        $motherVessels = MotherVessel::getDropDownList();

        return view('mother-vessel-carrings.create', compact('code', 'lcNumber', 'motherVessels'));
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
            'mother_vessel_id'  => 'required|integer',
            'carring_date'  => 'required|date|date_format:Y-m-d',
            'loading_date'  => 'required|date|date_format:Y-m-d',
            'received_date'  => 'required|date|date_format:Y-m-d',
            'invoice_quantity'  => 'required|numeric|min:1',
            'received_quantity'  => 'required|numeric|min:0'.($request->filled('invoice_quantity') && is_numeric(trim($request->invoice_quantity)) ? '|max:'.trim($request->invoice_quantity) : ''),
            'transport_loss'  => 'required|numeric',
        ];

        if(!$request->has('mother_vessel_carring_id')) {
            $rules = $rules + [
                'code' => 'required|alpha_num|min:6|max:10|unique:mother_vessel_carrings',
                'lc_number' => 'required|alpha_num|min:8|max:40|unique:mother_vessel_carrings',
            ];
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $motherVesselCarring = !$request->has('mother_vessel_carring_id') ? new MotherVesselCarring : MotherVesselCarring::findOrFail($request->mother_vessel_carring_id);
            
            $motherVesselCarring->mother_vessel_id = trim($request->mother_vessel_id);

            if(!$request->has('mother_vessel_carring_id')) {
                $motherVesselCarring->code = trim($request->code);
                $motherVesselCarring->lc_number = trim($request->lc_number);
                $received_quantity_old = 0;
            }else {
                $received_quantity_old = $motherVesselCarring->received_quantity;
            }

            $motherVesselCarring->carring_date = trim($request->carring_date);
            $motherVesselCarring->loading_date = trim($request->loading_date);
            $motherVesselCarring->received_date = trim($request->received_date);
            $motherVesselCarring->invoice_quantity = trim($request->invoice_quantity);
            $motherVesselCarring->received_quantity = trim($request->received_quantity);
            $motherVesselCarring->transport_loss = trim($request->transport_loss);
            $motherVesselCarring->comment = trim($request->comment);
            
            if(!$request->has('mother_vessel_carring_id')) {
                $motherVesselCarring->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $motherVesselCarring->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($motherVesselCarring->save()) { 

                // update termial stock
                $terminal = Terminal::find(1);
                $terminal->stock = $terminal->stock - $received_quantity_old + $motherVesselCarring->received_quantity;
                $terminal->save();

                $message = toastMessage ( " Mother vessel carring has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " Mother vessel carring has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('mother-vessel-carrings/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MotherVesselCarring  $motherVesselCarring
     * @return \Illuminate\Http\Response
     */
    public function show(MotherVesselCarring $motherVesselCarring)
    {
        return view('mother-vessel-carrings.show', compact('motherVesselCarring'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MotherVesselCarring  $motherVesselCarring
     * @return \Illuminate\Http\Response
     */
    public function edit(MotherVesselCarring $motherVesselCarring)
    {
        $motherVessels = MotherVessel::getDropDownList();

        return view('mother-vessel-carrings.edit', compact('motherVessels', 'motherVesselCarring'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(MotherVesselCarring::destroy($request->hdnResource)) {
            $message = toastMessage('Mother vessel carring has been successfully removed.','success');
        }else{
            $message = toastMessage('Mother vessel carring has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }
}
