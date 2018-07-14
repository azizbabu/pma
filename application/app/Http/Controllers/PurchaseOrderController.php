<?php

namespace App\Http\Controllers;

use App\PurchaseOrder;
use Illuminate\Http\Request;
use App\Item;
use App\Plant;
use App\PurchaseRequisition;
use Carbon, DB, Validator;

class PurchaseOrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission')->except(['getList', 'fetchItem', 'changeApproveStatus', 'getPurchaseRequisitions']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getListOld(Request $request)
    {
        $query = PurchaseOrder::query();

        $data = [];

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));
                
            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('item_id')) {
            $query->where('item_id', trim($request->item_id));
                
            $data['item_id'] = trim($request->item_id);
        }

        if($request->filled('po_number')) {
            $query->where('po_number', 'LIKE', '%'. trim($request->po_number) . '%');

            $data['po_number'] = trim($request->po_number);
        }

        if($request->filled('po_date')) {
            $query->where('po_date', trim($request->po_date));
                
            $data['po_date'] = trim($request->po_date);
        }

        $purchaseOrders = $query->latest()->paginate();

        $purchaseOrders->paginationSummary = getPaginationSummary($purchaseOrders->total(), $purchaseOrders->perPage(), $purchaseOrders->currentPage());

        if($data) {
            $purchaseOrders->appends($data);
        }

        $plants = Plant::getDropDownlist();
        $items = Item::getDropDownlist();

        return view('purchase-orders.index-old', compact('purchaseOrders', 'plants', 'items'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = PurchaseOrder::select(
            'po_number',
            'po_date',
            'approved_by',
            DB::raw('COUNT(item_id) AS item_qty'),
            DB::raw('SUM(pr_qty) AS pr_qty'),
            DB::raw('SUM(po_qty) AS po_qty'),
            DB::raw('SUM(po_price) AS po_price'),
            DB::raw('SUM(po_value) AS po_value')
        );

        $data = [];

        if($request->filled('po_number')) {
            $query->where('po_number', 'LIKE', '%'. trim($request->po_number) . '%');

            $data['po_number'] = trim($request->po_number);
        }

        if($request->filled('po_date')) {
            $query->where('po_date', trim($request->po_date));
                
            $data['po_date'] = trim($request->po_date);
        }

        $purchaseOrders = $query->groupBy('po_number')->latest()->paginate();

        $purchaseOrders->paginationSummary = getPaginationSummary($purchaseOrders->total(), $purchaseOrders->perPage(), $purchaseOrders->currentPage());

        if($data) {
            $purchaseOrders->appends($data);
        }

        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $po_number = PurchaseOrder::getPoNumber();
        $plants = Plant::getDropDownlist();
        $purchaseRequisitions = PurchaseRequisition::getDropDownList();

        return view('purchase-orders.create', compact('po_number', 'plants', 'purchaseRequisitions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules =[];
        if(!$request->has('po_number')) {
            $rules['plant_id'] = 'required|integer|min:1';
        }

        $rules = $rules + [
            'po_date'  => 'required|date|date_format:Y-m-d',
            'spare_parts_type'  => 'required|max:20',
            'source_type'  => 'required|max:20',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'status'    => 400,
                'error' => $validator->errors(),
            ]);
        }
        
        $total_purchase_order_id = count($request->purchase_order_id);
        $total_purchase_requisition_id = count($request->purchase_requisition_id);
        $total_requisition_code = count($request->requisition_code);
        $total_item = count($request->item_id);
        $total_last_price = count($request->last_price);
        $total_pr_qty = count($request->pr_qty);
        $total_po_qty = count($request->po_qty);
        $total_po_price = count($request->po_price);

        if($total_purchase_order_id == $total_purchase_requisition_id && $total_purchase_requisition_id == $total_requisition_code && $total_requisition_code == $total_item && $total_item == $total_last_price && $total_last_price==$total_pr_qty && $total_pr_qty == $total_po_qty && $total_po_qty == $total_po_price && $total_po_price) {
            
            for ($i=0; $i < $total_item; $i++) { 
                $purchaseRequisition = PurchaseRequisition::find($request->purchase_requisition_id[$i]);

                if(!$purchaseRequisition) {
                    return response()->json([
                        'type'  => 'error',
                        'message' => 'Purchase requisition not found!'
                    ]);
                }

                $po_qty_old = 0;
                if($request->purchase_order_id[$i]) {
                    $purchaseOrder = PurchaseOrder::find($request->purchase_order_id[$i]);
                    $po_qty_old = $purchaseOrder->po_qty;
                }

                $rules['purchase_order_id.'.$i] = 'required|integer|min:0';
                $rules['purchase_requisition_id.'.$i] = 'required|integer|min:1';
                $rules['requisition_code.'.$i] = 'required|string';
                $rules['item_id.'.$i] = 'required|integer|min:1';
                $rules['last_price.'.$i] = 'required|numeric|min:0';
                $rules['pr_qty.'.$i] = 'required|integer|min:0';
                $rules['po_qty.'.$i] = 'required|integer|min:1|max:'.($purchaseRequisition->remaining_qty + $po_qty_old);
                $rules['po_price.'.$i] = 'required|numeric|min:1';
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

            // Delete purchase order if po number already exists
            if($request->has('po_number')) {
                $purchaseOrders = PurchaseOrder::wherePoNumber(trim($request->po_number))->get();

                if($purchaseOrders->isNotEmpty()) {
                    // Update pr qty and pipeline qty of item 
                    // foreach($purchaseOrders as $purchaseOrder) {
                    //     $item = $purchaseOrder->item;
                    //     $item->po_qty = $item->po_qty - $purchaseOrder->po_qty;
                    //     $item->save();
                    // }

                    // PurchaseOrder::wherePoNumber(trim($request->po_number))->forceDelete();

                    $po_number = trim($request->po_number);
                }
            }

            $po_number = !empty($po_number) ? $po_number : PurchaseOrder::getPoNumber();
            $insert_item_number = 0;

            // insert 
            for ($i=0; $i < $total_item; $i++) { 

                $item = Item::find($request->item_id[$i]);

                if($item) {
                    $purchaseOrder = !$request->purchase_order_id[$i] ? new PurchaseOrder : PurchaseOrder::find($request->purchase_order_id[$i]);
                    if(!$request->has('po_number')) {
                        $purchaseOrder->plant_id = trim($request->plant_id);
                    }
                    $purchaseOrder->po_number = $po_number;
                    $purchaseOrder->po_date = trim($request->po_date); 
                    $purchaseOrder->spare_parts_type = trim($request->spare_parts_type); 
                    $purchaseOrder->source_type = trim($request->source_type);

                    $purchaseOrder->item_id = $item->id;
                    $purchaseOrder->purchase_requisition_id = trim($request->purchase_requisition_id[$i]);
                    $purchaseOrder->requisition_code = trim($request->requisition_code[$i]);
                    $purchaseOrder->last_price = trim($request->last_price[$i]);
                    $purchaseOrder->pr_qty = $request->pr_qty[$i];
                    $po_qty_old = 0;
                    if($request->has('po_number')) {
                        $po_qty_old = $purchaseOrder->po_qty;
                    }
                    $purchaseOrder->po_qty = $request->po_qty[$i];
                    $purchaseOrder->po_price = $request->po_price[$i];
                    $purchaseOrder->po_value = $request->po_qty[$i] * $request->po_price[$i];
                    $purchaseOrder->remarks = $request->remarks[$i];
                    $purchaseOrder->created_by = $request->user()->id;

                    if($request->has('po_number')) {
                        $purchaseOrder->updated_by = $request->user()->id;
                        $msg = 'updated.';
                    }else {
                        $msg = 'added.';
                    }

                    if($purchaseOrder->save()) {
                        // update purchase requisition
                        $purchaseRequisition = $purchaseOrder->purchaseRequisition; 
                        $purchaseRequisition->remaining_qty = $purchaseRequisition->remaining_qty + $po_qty_old - $purchaseOrder->po_qty; 
                        $purchaseRequisition->save();

                        // update item po qty
                        $item->po_qty = $item->po_qty + $request->po_qty[$i] - $po_qty_old;
                        $item->save();
                        
                        $insert_item_number++;
                    }
                }
            }
            
            if($insert_item_number) {
                return response()->json([
                    'type'  => 'success',
                    'message'   => ' Purchase order(s) have been successfully '. $msg,
                ]);
            }

            return response()->json([
                'type'  => 'error',
                'message'   => ' No purchase order added.',
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
     * @param  string $po_number
     * @return \Illuminate\Http\Response
     */
    public function show($po_number)
    {
        $purchaseOrders = PurchaseOrder::wherePoNumber($po_number)->get();
        $purchaseOrder = $purchaseOrders->first();

        return view('purchase-orders.show', compact('purchaseOrders', 'purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $po_number
     * @return \Illuminate\Http\Response
     */
    public function edit($po_number)
    {
        $purchaseOrders = PurchaseOrder::wherePoNumber($po_number)->get();
        $purchaseOrder = $purchaseOrders->first();
        $po_number = $purchaseOrder->po_number;
        $plants = Plant::getDropDownlist();
        $purchaseRequisitions = PurchaseRequisition::getDropDownList();

        return view('purchase-orders.edit', compact('purchaseOrders', 'purchaseOrder', 'po_number', 'plants', 'purchaseRequisitions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $rules = [
            'pr_qty'   => 'required|integer|min:0',
            'po_qty'   => 'required|integer|min:0',
            'spare_parts_type'  => 'required|max:20',
            'source_type'  => 'required|max:20',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }else {
            // update
            $purchaseOrder->spare_parts_type = trim($request->spare_parts_type); 
            $purchaseOrder->source_type = trim($request->source_type);
            $purchaseOrder->pr_qty = trim($request->pr_qty);
            $purchaseOrder->po_qty = trim($request->po_qty);
            $purchaseOrder->remarks = trim($request->remarks);
            $purchaseOrder->updated_by = $request->user()->id;

            if($purchaseOrder->save()) {
                $message = toastMessage('Purchase order has been successfully updated');
            }else {
                $message = toastMessage('Purchase order has not been updated', 'error');
            }

            session()->flash('toast', $message);

            return redirect('purchase-orders/list');
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
        if(PurchaseOrder::wherePoNumber($request->hdnResource)->delete()) {
            $message = toastMessage('Purchase order has been successfully removed.','success');
        }else{
            $message = toastMessage('Purchase order has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }

    /**
     * Change approve status
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeApproveStatus(Request $request)
    {
        $purchaseOrders = PurchaseOrder::wherePoNumber($request->hdnResource)->get(['id', 'approved_by', 'approved_date', 'updated_by']);

        if($purchaseOrders->isNotEmpty()) {
            $approved_item = 0;
            foreach($purchaseOrders as $purchaseOrder) {
                if(!$purchaseOrder->approved_by) {
                    $purchaseOrder->approved_by = $request->user()->id;
                    $purchaseOrder->approved_date = date('Y-m-d');
                    $msg = 'approved';
                }else {
                    $purchaseOrder->approved_by = 0;
                    $purchaseOrder->approved_date = null;
                    $msg = 'unapproved';
                }
                $purchaseOrder->updated_by = $request->user()->id;

                if($purchaseOrder->save()) {
                    $approved_item++;
                }
            }
        }

        if($approved_item) {
            $message = toastMessage('Purchase order has been '. $msg);
        }else {
            $message = toastMessage('Purchase order has not been '. $msg, 'error');
        }

        session()->flash('toast', $message);

        return back();
    }

    /**
     * Get json info of item
     *
     * @param \Illuminate\Http\Request $request
     * @param int id
     * @return \Illuminate\Http\Response
     */
    public function fetchItem(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::whereItemId($id)->first(['id']);
        $query = DB::table('items AS i')->leftJoin('purchase_orders AS po', 'i.id', '=', 'po.item_id')
            ->where('i.id', $id)
            ->select(
                'i.id', 
                'i.name', 
                'i.avg_price', 
                'i.pipeline_qty',
                'i.remarks',
                DB::raw('IFNULL(po.po_price, 0) as po_price')
            );

        if($purchaseOrder) {
            $query->latest('po.po_price');
        }
            
        $item = $query->first();

        if(!$item) {
            return response()->json([
                'type'  => 'error',
                'message'   => 'Item not found!',
            ]);
        }

        return json_encode($item);
    }

    /**
     * Get json info of purchase requisitions based on plant id
     *
     * @param \Illuminate\Http\Request $request
     * @param int plant_id
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseRequisitions(Request $request, $plant_id) 
    {
        $purchaseOrder = PurchaseOrder::wherePlantId($plant_id)->first(['id']);

        $query = DB::table('purchase_requisitions AS pr')
            ->leftJoin('purchase_orders AS po', 'pr.id', '=', 'po.purchase_requisition_id')
            ->join('items AS i', 'pr.item_id', '=', 'i.id')
            ->select(
                'pr.id', 
                'pr.requisition_code', 
                'pr.remaining_qty',
                'i.id AS item_id',
                'i.name',
                'i.avg_price',
                'i.pipeline_qty AS pr_qty',
                DB::raw('IFNULL(po.po_price, 0) as last_price')
            )->where('pr.plant_id', $plant_id)
            ->where('pr.remaining_qty', '>', 0);

        if($purchaseOrder) {
            $query->latest('po.po_price');
        }

        $purchaseRequisitions = $query->groupBy('pr.id')->get();

        // dd($purchaseRequisitions);

        return $purchaseRequisitions->toJson();
    }
}
