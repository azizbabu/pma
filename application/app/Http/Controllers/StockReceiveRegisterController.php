<?php

namespace App\Http\Controllers;

use App\StockReceiveRegister;
use Illuminate\Http\Request;
use App\Item;
use App\Plant;
use App\PurchaseOrder;
use Carbon, DB, Validator;

class StockReceiveRegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission')->except(['getList', 'getPurchaseOrders', 'changeApproveStatus']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getListOld(Request $request)
    {
        $query = StockReceiveRegister::query();

        $data = [];

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));
                
            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('item_id')) {
            $query->where('item_id', trim($request->item_id));
                
            $data['item_id'] = trim($request->item_id);
        }

        if($request->filled('receive_code')) {
            $query->where('receive_code', 'LIKE', '%'. trim($request->receive_code) . '%');

            $data['receive_code'] = trim($request->receive_code);
        }

        if($request->filled('receive_date')) {
            $query->where('receive_date', trim($request->receive_date));
                
            $data['receive_date'] = trim($request->receive_date);
        }

        $stockReceiveRegisters = $query->latest()->paginate();

        $stockReceiveRegisters->paginationSummary = getPaginationSummary($stockReceiveRegisters->total(), $stockReceiveRegisters->perPage(), $stockReceiveRegisters->currentPage());

        if($data) {
            $stockReceiveRegisters->appends($data);
        }

        $plants = Plant::getDropDownlist();
        $items = Item::getDropDownlist();

        return view('stock-receive-registers.index-old', compact('stockReceiveRegisters', 'plants', 'items'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = StockReceiveRegister::select(
            'receive_code',
            'receive_date',
            DB::raw('COUNT(item_id) AS item_qty'),
            DB::raw('SUM(po_qty) AS po_qty'),
            DB::raw('SUM(grn_qty) AS grn_qty'),
            'approved_by'
        );

        $data = [];

        if($request->filled('receive_code')) {
            $query->where('receive_code', 'LIKE', '%'. trim($request->receive_code) . '%');

            $data['receive_code'] = trim($request->receive_code);
        }

        if($request->filled('receive_date')) {
            $query->where('receive_date', trim($request->receive_date));
                
            $data['receive_date'] = trim($request->receive_date);
        }

        $stockReceiveRegisters = $query->groupBy('receive_code')->latest()->paginate();

        $stockReceiveRegisters->paginationSummary = getPaginationSummary($stockReceiveRegisters->total(), $stockReceiveRegisters->perPage(), $stockReceiveRegisters->currentPage());

        if($data) {
            $stockReceiveRegisters->appends($data);
        }

        $plants = Plant::getDropDownlist();

        return view('stock-receive-registers.index', compact('stockReceiveRegisters', 'plants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $receive_code = StockReceiveRegister::getReceiveCode();
        $plants = Plant::getDropDownList();
        $purchaseOrders = PurchaseOrder::getDropDownList();

        return view('stock-receive-registers.create', compact('receive_code', 'plants', 'purchaseOrders'));
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
            'receive_date'  => 'required|date|date_format:Y-m-d',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'status'    => 400,
                'error' => $validator->errors(),
            ]);
        }

        $total_po_number = count($request->po_number);
        $total_requisition_code = count($request->requisition_code);
        $total_item_id = count($request->item_id);
        $total_grn_qty = count($request->grn_qty);
        $total_po_qty = count($request->po_qty);

        if($total_requisition_code == $total_item_id && $total_item_id == $total_grn_qty && $total_grn_qty == $total_po_qty && $total_po_qty) {
            
            for ($i=0; $i < $total_item_id; $i++) { 
                $rules['requisition_code.'.$i] = 'required|string|max:12';
                $rules['requisition_code.'.$i] = 'required|string|max:12:unique:stock_receive_registers';
                $rules['item_id.'.$i] = 'required|integer|min:1';
                $rules['grn_qty.'.$i] = 'required|integer|min:1';
                $rules['po_qty.'.$i] = 'required|integer|min:1';
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

            // Delete stock receive register if receive code already exists
            if($request->has('receive_code')) {
                $stockReceiveRegisters = StockReceiveRegister::whereReceiveCode(trim($request->receive_code))->get();

                if($stockReceiveRegisters->isNotEmpty()) {
                    // Update pr qty and pipeline qty of item 
                    foreach($stockReceiveRegisters as $stockReceiveRegister) {
                        $item = $stockReceiveRegister->item;
                        $item->receive_qty = $item->receive_qty - $stockReceiveRegister->grn_qty;
                        $item->save();
                    }

                    StockReceiveRegister::whereReceiveCode(trim($request->receive_code))->forceDelete();

                    $receive_code = trim($request->receive_code);
                }
            }

            $receive_code = !empty($receive_code) ? $receive_code :StockReceiveRegister::getReceiveCode();
            $insert_item_number = 0;

            // insert 
            for ($i=0; $i < $total_item_id; $i++) { 

                $item = Item::find($request->item_id[$i]);

                if($item) {
                    
                    $stockReceiveRegister = new StockReceiveRegister;
                    $stockReceiveRegister->plant_id = trim($request->plant_id);
                    $stockReceiveRegister->receive_code = $receive_code;
                    $stockReceiveRegister->receive_date = trim($request->receive_date);
                    $stockReceiveRegister->po_number = $request->po_number[$i];
                    $stockReceiveRegister->item_id = $item->id;
                    $stockReceiveRegister->requisition_code = $request->requisition_code[$i];
                    $stockReceiveRegister->grn_qty = $request->grn_qty[$i];
                    $stockReceiveRegister->po_qty = $request->po_qty[$i];
                    $stockReceiveRegister->remarks = $request->remarks[$i];
                    $stockReceiveRegister->created_by = $request->user()->id;

                    if($request->has('receive_code')) {
                        $stockReceiveRegister->updated_by = $request->user()->id;
                        $msg = 'updated.';
                    }else {
                        $msg = 'added.';
                    }

                    if($stockReceiveRegister->save()) {
                        // update item receive qty
                        $item->receive_qty = $item->receive_qty + $request->grn_qty[$i];
                        $item->save();

                        $insert_item_number++;
                    }
                }
            }
            
            if($insert_item_number) {
                return response()->json([
                    'type'  => 'success',
                    'message'   => ' Stock receive register info have been successfully '. $msg,
                ]);
            }

            return response()->json([
                'type'  => 'error',
                'message'   => ' Stock receive register info has not '. $msg,
            ]);
        }

        return response()->json([
            'type'  => 'error',
            'message'   => 'Please item(s) correctly',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $receive_code
     * @return \Illuminate\Http\Response
     */
    public function show($receive_code)
    {
        $stockReceiveRegisters = StockReceiveRegister::whereReceiveCode($receive_code)->get();

        if($stockReceiveRegisters->isEmpty()) {
            session()->flash('toast', toastMessage('Stock receive register info not found!', 'error'));

            return back();
        }

        $stockReceiveRegister = $stockReceiveRegisters->first();

        return view('stock-receive-registers.show', compact('stockReceiveRegisters', 'stockReceiveRegister'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $receive_code
     * @return \Illuminate\Http\Response
     */
    public function edit($receive_code)
    {
        $stockReceiveRegisters = StockReceiveRegister::whereReceiveCode($receive_code)->get();

        if($stockReceiveRegisters->isEmpty()) {
            session()->flash('toast', toastMessage('Stock receive register info not found!', 'error'));

            return back();
        }

        $stockReceiveRegister = $stockReceiveRegisters->first();
        $receive_code = $stockReceiveRegister->receive_code;
        $plants = Plant::getDropDownList();
        $purchaseOrders = PurchaseOrder::getDropDownList();

        return view('stock-receive-registers.edit', compact('stockReceiveRegisters', 'stockReceiveRegister', 'receive_code', 'plants', 'purchaseOrders'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockReceiveRegister  $stockReceiveRegister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockReceiveRegister $stockReceiveRegister)
    {
        $rules = [
            'grn_qty'   => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }else {
            // update
            $stockReceiveRegister->grn_qty = trim($request->grn_qty);
            $stockReceiveRegister->remarks = trim($request->remarks);
            $stockReceiveRegister->updated_by = $request->user()->id;

            if($stockReceiveRegister->save()) {
                $message = toastMessage('Stock receive register has been successfully updated');
            }else {
                $message = toastMessage('Stock receive register has not been updated', 'error');
            }

            session()->flash('toast', $message);

            return redirect('stock-receive-registers/list');
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
        if(StockReceiveRegister::whereReceiveCode($request->hdnResource)->delete()) {
            $message = toastMessage('Stock receive register has been successfully removed.','success');
        }else{
            $message = toastMessage('Stock receive register has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }

    /**
     * Get purchase order info based on po number
     *
     * @param  \Illuminate\Http\Request $request
     * @param $po_number
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseOrders(Request $request, $po_number)
    {
        $stockReceiveRegisters = DB::table('purchase_orders as po')
            ->join('items as i', 'po.item_id', '=', 'i.id')
            ->select('po.po_number', 'po.requisition_code', 'po.po_qty', 'i.id', 'i.code', 'i.name')
            ->where('po.po_number', $po_number)
            ->get();

        return json_encode($stockReceiveRegisters);
    }

    /**
     * Change approve status
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeApproveStatus(Request $request)
    {
        $stockReceiveRegisters = StockReceiveRegister::whereReceiveCode($request->hdnResource)->get();

        if($stockReceiveRegisters->isEmpty()) {
            session()->flash('toast', toastMessage('Stock receive register not found!', 'error'));

            return back();
        }        

        $approved_item_number = 0;

        foreach($stockReceiveRegisters as $stockReceiveRegister) {
            if(!$stockReceiveRegister->approved_by) {
                $stockReceiveRegister->approved_by = $request->user()->id;
                $stockReceiveRegister->approved_date = date('Y-m-d');
                $msg = 'approved';
            }else {
                $stockReceiveRegister->approved_by = 0;
                $stockReceiveRegister->approved_date = null;
                $msg = 'unapproved';
            }
            $stockReceiveRegister->updated_by = $request->user()->id;

            if($stockReceiveRegister->save()) {
                $approved_item_number++;
            }
        }

        if($approved_item_number) {
            $message = toastMessage('Stock receive register has been '. $msg);
        }else {
            $message = toastMessage('Stock receive register has not been '. $msg, 'error');
        }

        session()->flash('toast', $message);

        return back();
    }
}
