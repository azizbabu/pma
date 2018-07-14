<?php

namespace App\Http\Controllers;

use App\FuelTrade;
use Illuminate\Http\Request;
use App\Party;
use App\Terminal;
use Carbon, Validator;

class FuelTradeController extends Controller
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
        $query = FuelTrade::query();

        $data = [];

        if($request->filled('transaction_date')) {
            $query->where('transaction_date', trim($request->transaction_date));

            $data['transaction_date'] = trim($request->transaction_date);
        }

        if($request->filled('party_id')) {
            $query->where('party_id', trim($request->party_id));

            $data['party_id'] = trim($request->party_id);
        }

        if($request->filled('terminal_id')) {
            $query->where('terminal_id', trim($request->terminal_id));

            $data['terminal_id'] = trim($request->terminal_id);
        }

        $fuelTrades = $query->latest()->paginate();

        $fuelTrades->paginationSummary = getPaginationSummary($fuelTrades->total(), $fuelTrades->perPage(), $fuelTrades->currentPage());

        if($data) {
            $fuelTrades->appends($data);
        }

        $terminals = Terminal::getDropDownList();
        $parties = Party::getDropDownList();

        return view('fuel-trades.index', compact('fuelTrades', 'terminals', 'parties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parties = Party::getDropDownList();
        $terminals = Terminal::getDropDownList();

        return view('fuel-trades.create', compact('parties', 'terminals'));
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
            'party_id'  => 'required|integer|min:1',
            'terminal_id'  => 'required|integer|min:1',
            'transaction_date'  => 'required|date|date_format:Y-m-d',
            'loan_given_qty'  => 'required|numeric|min:0',
            'loan_receive_qty'  => 'required|numeric|min:0',
            'loan_return_qty'  => 'required|numeric|min:0',
            'loan_paid_by_party_qty'  => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $fuelTrade = !$request->has('fuel_trade_id') ? new FuelTrade : FuelTrade::findOrFail($request->fuel_trade_id);
            
            $fuelTrade->party_id = trim($request->party_id);
            $fuelTrade->terminal_id = trim($request->terminal_id);
            $fuelTrade->transaction_date = trim($request->transaction_date);
            $fuelTrade->loan_given_qty = trim($request->loan_given_qty);
            $fuelTrade->loan_receive_qty = trim($request->loan_receive_qty);
            $fuelTrade->loan_return_qty = trim($request->loan_return_qty);
            $fuelTrade->loan_paid_by_party_qty = trim($request->loan_paid_by_party_qty);
            
            if(!$request->has('fuel_trade_id')) {
                $fuelTrade->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $fuelTrade->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($fuelTrade->save()) { 
                $message = toastMessage ( " Fuel trade has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " Fuel trade has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('fuel-trades/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FuelTrade  $fuelTrade
     * @return \Illuminate\Http\Response
     */
    public function show(FuelTrade $fuelTrade)
    {
        return view('fuel-trades.show', compact('fuelTrade'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FuelTrade  $fuelTrade
     * @return \Illuminate\Http\Response
     */
    public function edit(FuelTrade $fuelTrade)
    {
        $parties = Party::getDropDownList();
        $terminals = Terminal::getDropDownList();

        return view('fuel-trades.edit', compact('fuelTrade', 'parties', 'terminals'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(FuelTrade::destroy($request->hdnResource)) {
            $message = toastMessage('Fuel trade has been successfully removed.','success');
        }else{
            $message = toastMessage('Fuel trade has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }
}
