<?php

namespace App\Http\Controllers;

use App\DailyTerminalStock;
use Illuminate\Http\Request;
use App\Tank;
use App\Terminal;
use Carbon, Validator;

class DailyTerminalStockController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission')->except('getList', 'getTanks');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = DailyTerminalStock::query();

        $data = [];

        if($request->filled('transaction_date')) {
            $query->where('transaction_date', trim($request->transaction_date));

            $data['transaction_date'] = trim($request->transaction_date);
        }

        if($request->filled('terminal_id')) {
            $query->where('terminal_id', trim($request->terminal_id));

            $data['terminal_id'] = trim($request->terminal_id);
        }

        if($request->filled('tank_id')) {
            $query->where('tank_id', trim($request->tank_id));

            $data['tank_id'] = trim($request->tank_id);
        }

        $dailyTerminalStocks = $query->latest()->paginate();

        $dailyTerminalStocks->paginationSummary = getPaginationSummary($dailyTerminalStocks->total(), $dailyTerminalStocks->perPage(), $dailyTerminalStocks->currentPage());

        if($data) {
            $dailyTerminalStocks->appends($data);
        }

        $terminals = Terminal::getDropDownList();

        return view('daily-terminal-stocks.index', compact('dailyTerminalStocks', 'terminals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $terminals = Terminal::getDropDownList();

        return view('daily-terminal-stocks.create', compact('terminals'));
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
            'transaction_date'  => 'required|date|date_format:Y-m-d',
            'terminal_id'  => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    => 400,
                'error'     => $validator->errors(),
            ]);
        }else{

            if(!$request->has('tank_id')) {
                return response()->json([
                    'status'    => 404,
                    'type'      => 'error',
                    'message'   => 'The tank field is required',
                ]);
            }

            foreach ($request->tank_id as $key => $value) {
                $rules['tank_id.'.$key] = 'required|integer|min:1';
                $rules['tank_stock.'.$key] = 'required|numeric|min:0';
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

            $addedStockNumber = 0;
            $total_tank_id = count($request->tank_id);
            $total_tank_stock = count($request->tank_stock);
            $total_comment = count($request->comment);

            if($total_tank_id == $total_tank_stock && $total_tank_stock == $total_comment && $total_tank_id) {

                // Delete already added daily terminal stocks
                DailyTerminalStock::whereTransactionDate(trim($request->transaction_date))->whereTerminalId(trim($request->terminal_id))->forceDelete();

                foreach($request->tank_id as $key => $value) { 
                    // store

                    $tank = Tank::find($request->tank_id[$key]);
                    if($tank) {
                        $dailyTerminalStock = new DailyTerminalStock;
                        $dailyTerminalStock->terminal_id = trim($request->terminal_id);
                        $dailyTerminalStock->transaction_date = trim($request->transaction_date);
                        $dailyTerminalStock->tank_id = $request->tank_id[$key];
                        $dailyTerminalStock->tank_number = $tank->number;
                        $dailyTerminalStock->tank_stock = $request->tank_stock[$key];
                        $dailyTerminalStock->comment = $request->comment[$key];
                        $dailyTerminalStock->created_by = $request->user()->id;

                        if($dailyTerminalStock->save()) {
                            $addedStockNumber++;
                        }
                    }
                }
            }else {
                return response()->json([
                    'type'      => 'error',
                    'message'  => 'Please fill tank information currectly',
                ]);
            }

            if($addedStockNumber) {
                return response()->json([
                    'status'    => 200,
                    'type'    => 'success',
                    'message'    => $addedStockNumber . ' no transaction stock(s) have been successfully added.',
                ]);
            }else {
                return response()->json([
                    'status'    => 404,
                    'type'    => 'error',
                    'message'    => 'Permission has not been added.',
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DailyTerminalStock  $dailyTerminalStock
     * @return \Illuminate\Http\Response
     */
    public function show(DailyTerminalStock $dailyTerminalStock)
    {
        return view('daily-terminal-stocks.show', compact('dailyTerminalStock'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DailyTerminalStock  $dailyTerminalStock
     * @return \Illuminate\Http\Response
     */
    public function edit(DailyTerminalStock $dailyTerminalStock)
    {
        return view('daily-terminal-stocks.edit', compact('dailyTerminalStock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DailyTerminalStock  $dailyTerminalStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DailyTerminalStock $dailyTerminalStock)
    {
        $rules = [
            'transaction_date'  => 'required|date|date_format:Y-m-d',
        ];

        if($request->filled('tank_stock')) {
            $rules = $rules + [
                'tank_stock' => 'numeric|min:0',
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }else {
            // update
            $dailyTerminalStock->transaction_date = trim($request->transaction_date);
            $dailyTerminalStock->tank_stock = trim($request->tank_stock);
            $dailyTerminalStock->comment = trim($request->comment);
            $dailyTerminalStock->updated_by = $request->user()->id;

            if($dailyTerminalStock->save())  {
                $message = toastMessage('Daily terminal stock has been successfully updated');
            }else {
                $message = toastMessage('Daily terminal stock has not been updated', 'error');
            }

            session()->flash('toast', $message);

            return redirect('daily-terminal-stocks/list');
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
        if(DailyTerminalStock::destroy($request->hdnResource)) {
            $message = toastMessage('Daily terminal stock has been successfully removed.','success');
        }else{
            $message = toastMessage('Daily terminal stock has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }

    /**
     * Get tanks based on terminal id
     *
     * @param \Illuminate\Http\Request $request.
     * @return \Illuminate\Http\Response
     */
    public function getTanks(Request $request)
    {
        $rules = [
            'transaction_date'  => 'required|date|date_format:Y-m-d',
            'terminal_id'  => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json([
                'status'    => 400,
                'error' => $validator->errors(),
            ]);
        }

        $terminal = Terminal::find(trim($request->terminal_id));

        if(!$terminal) {
            return response()->json([
                'type'      => 'error',
                'message'   => 'Terminal not found!',
            ]);
        }

        $tanks = $terminal->tanks()->get(['id', 'number', 'capacity']);

        foreach($tanks as $tank) {
            
            $dailyTerminalStock = $tank->dailyTerminalStocks()->whereTransactionDate(trim($request->transaction_date))->first([
                'tank_stock', 
                'comment'
            ]);

            if($dailyTerminalStock) {
                $tank_stock = $dailyTerminalStock->tank_stock;
                $comment = $dailyTerminalStock->comment;
            }else {
                $tank_stock = 0;
                $comment = null;
            }

            $tank->tank_stock = $tank_stock;
            $tank->comment = $comment;
        }

        return $tanks->toJson();
    }
}
