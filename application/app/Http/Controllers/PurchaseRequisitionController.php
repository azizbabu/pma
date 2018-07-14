<?php

namespace App\Http\Controllers;

use App\PurchaseRequisition;
use Illuminate\Http\Request;
use App\Item;
use App\Plant;
use Carbon, DB, Validator;

class PurchaseRequisitionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission')->except(['getList', 'changeApproveStatus', 'fetchPrItems']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getListOld(Request $request)
    {
        $query = PurchaseRequisition::query();

        $data = [];

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));
                
            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('item_id')) {
            $query->where('item_id', trim($request->item_id));
                
            $data['item_id'] = trim($request->item_id);
        }

        if($request->filled('requisition_code')) {
            $query->where('requisition_code', 'LIKE', '%'. trim($request->requisition_code) . '%');

            $data['requisition_code'] = trim($request->requisition_code);
        }

        if($request->filled('requisition_date')) {
            $query->where('requisition_date', trim($request->requisition_date));
                
            $data['requisition_date'] = trim($request->requisition_date);
        }

        $purchaseRequisitions = $query->latest('id')->paginate();

        $purchaseRequisitions->paginationSummary = getPaginationSummary($purchaseRequisitions->total(), $purchaseRequisitions->perPage(), $purchaseRequisitions->currentPage());

        if($data) {
            $purchaseRequisitions->appends($data);
        }

        $plants = Plant::getDropDownlist();
        $items = Item::getDropDownlist();

        return view('purchase-requisitions.index-old', compact('purchaseRequisitions', 'plants', 'items'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = PurchaseRequisition::select(
            'requisition_code',
            DB::raw('MAX(requisition_date) as req_date'),
            DB::raw('COUNT(item_id) AS item_qty'),
            DB::raw('SUM(required_qty) AS req_qty'),
            DB::raw('SUM(approved_qty) as apv_qty'),
            DB::raw('SUM(total_value) as total_amount')
        );

        if($request->filled('requisition_code')) {
            $query->where('requisition_code', 'LIKE', '%'. trim($request->requisition_code) . '%');

            $data['requisition_code'] = trim($request->requisition_code);
        }

        if($request->filled('requisition_date')) {
            $query->where('requisition_date', trim($request->requisition_date));
                
            $data['requisition_date'] = trim($request->requisition_date);
        }
        $purchaseRequisitions = $query->groupBy('requisition_code')->latest('id')->paginate();
        $purchaseRequisitions->paginationSummary = getPaginationSummary($purchaseRequisitions->total(), $purchaseRequisitions->perPage(), $purchaseRequisitions->currentPage());

        return view('purchase-requisitions.index', compact('purchaseRequisitions'));
    }

    public function editView($requisition_code)
    {
        $purchaseRequisitions = PurchaseRequisition::whereRequisitionCode($requisition_code)->get();

        return view('purchase-requisitions.edit-view', compact('purchaseRequisitions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $requisition_code = PurchaseRequisition::getRequisitionCode();
        $plants = Plant::getDropDownlist();

        return view('purchase-requisitions.create', compact('requisition_code', 'plants'));
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
            'requisition_date'  => 'required|date|date_format:Y-m-d',
            'spare_parts_type'  => 'required|max:20',
            'source_type'  => 'required|max:20',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'status'    => 400,
                'error' => $validator->errors(),
            ]);
        }else{
            $total_item_id = count($request->item_id);
            $total_item_safety_stock_qty = count($request->item_safety_stock_qty);
            $total_present_stock_qty = count($request->present_stock_qty);
            $total_pipeline_qty = count($request->pipeline_qty);
            $total_required_qty = count($request->required_qty);
            
            if($total_item_id == $total_item_safety_stock_qty && $total_item_safety_stock_qty == $total_present_stock_qty && $total_present_stock_qty == $total_required_qty && $total_required_qty == $total_pipeline_qty && $total_pipeline_qty) {
                
                for ($i=0; $i < $total_item_id; $i++) { 
                    $rules['item_id.'.$i] = 'required|integer|min:1';
                    $rules['item_safety_stock_qty.'.$i] = 'required|integer|min:1';
                    $rules['present_stock_qty.'.$i] = 'required|integer|min:1';
                    $rules['required_qty.'.$i] = 'required|integer|min:1';
                    $rules['pipeline_qty.'.$i] = 'required|integer|min:0';
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

                // Delete purchase requisition if requisition code already exists
                if($request->has('requisition_code')) {
                    $purchaseRequisitions = PurchaseRequisition::whereRequisitionCode(trim($request->requisition_code))->get();

                    if($purchaseRequisitions->isNotEmpty()) {
                        // Update pr qty and pipeline qty of item 
                        foreach($purchaseRequisitions as $purchaseRequisition) {
                            $item = $purchaseRequisition->item;
                            $item->pr_qty = $item->pr_qty - $purchaseRequisition->required_qty;

                            if($purchaseRequisition->approved_qty) {
                                $item->pipeline_qty = $item->pipeline_qty - $purchaseRequisition->approved_qty;
                            }
                            $item->save();
                        }

                        PurchaseRequisition::whereRequisitionCode(trim($request->requisition_code))->forceDelete();
 
                        $requisition_code = trim($request->requisition_code);
                    }
                }

                $requisition_code = !empty($requisition_code) ? $requisition_code : PurchaseRequisition::getRequisitionCode();
                $insert_item_number = 0;

                // insert 
                for ($i=0; $i < $total_item_id; $i++) { 

                    $item = Item::find($request->item_id[$i]);

                    if($item) {
                        $purchaseRequisition = new PurchaseRequisition;
                        $purchaseRequisition->plant_id = trim($request->plant_id);
                        $purchaseRequisition->requisition_code = $requisition_code;
                        $purchaseRequisition->requisition_date = trim($request->requisition_date); 
                        $purchaseRequisition->spare_parts_type = trim($request->spare_parts_type); 
                        $purchaseRequisition->source_type = trim($request->source_type);

                        $purchaseRequisition->item_id = $item->id;
                        $purchaseRequisition->item_avg_price = $item->avg_price;
                        $purchaseRequisition->item_safety_stock_qty = $request->item_safety_stock_qty[$i];
                        $purchaseRequisition->present_stock_qty = $request->present_stock_qty[$i];
                        $purchaseRequisition->required_qty = $request->required_qty[$i];
                        $purchaseRequisition->pipeline_qty = $request->pipeline_qty[$i];
                        $purchaseRequisition->total_value = $item->avg_price * $request->required_qty[$i];
                        $purchaseRequisition->remarks = $request->remarks[$i];
                        $purchaseRequisition->created_by = $request->user()->id;
                        if(!$request->has('requisition_code')) {
                            $msg = 'added.';
                        }else {
                            $purchaseRequisition->updated_by = $request->user()->id;
                            $msg = 'updated.';
                        }

                        if($purchaseRequisition->save()) {
                            // update item pr qty
                            $item->pr_qty = $item->pr_qty + $request->required_qty[$i];
                            $item->save();
                            
                            $insert_item_number++;
                        }
                    }
                }

                if($insert_item_number) {
                    return response()->json([
                        'type'  => 'success',
                        'message'   => ' Purchase requisition information have been successfully '. $msg,
                    ]);
                }else {
                    return response()->json([
                        'type'  => 'error',
                        'message'   => ' Purchase requisition information has not been '. $msg,
                    ]);
                }
            }else {
                return response()->json([
                    'type'  => 'error',
                    'message'   => 'Please item(s) correctly',
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string $requisition_code
     * @return \Illuminate\Http\Response
     */
    public function show($requisition_code)
    {
        $purchaseRequisitions = PurchaseRequisition::whereRequisitionCode($requisition_code)->get();

        if($purchaseRequisitions->isEmpty()) {
            session()->flash('toast', toastMessage('Purchase Requisition not found!', 'error'));

            return back();
        }

        $purchaseRequisition = $purchaseRequisitions->first();

        return view('purchase-requisitions.show', compact('purchaseRequisitions', 'purchaseRequisition'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $requisition_code
     * @return \Illuminate\Http\Response
     */
    public function edit($requisition_code)
    {
        $purchaseRequisitions = PurchaseRequisition::whereRequisitionCode($requisition_code)->get();

        if($purchaseRequisitions->isEmpty()) {
            session()->flash('toast', toastMessage('Purchase Requisition not found!', 'error'));

            return back();
        }
        
        $purchaseRequisition = $purchaseRequisitions->first();
        $requisition_code = $purchaseRequisition->requisition_code;
        $plants = Plant::getDropDownlist();
        $items = Item::getDropDownlist();

        return view('purchase-requisitions.edit', compact('purchaseRequisitions', 'purchaseRequisition', 'requisition_code', 'plants', 'items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PurchaseRequisition  $purchaseRequisition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseRequisition $purchaseRequisition)
    {
        $rules = [
            'item_safety_stock_qty'   => 'required|integer|min:1',
            'present_stock_qty'  => 'required|integer|min:1',
            'required_qty'  => 'required|integer|min:1',
            'pipeline_qty'  => 'required|integer|min:1',
            'spare_parts_type'  => 'required|max:20',
            'source_type'  => 'required|max:20',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }else {
            // update
            $purchaseRequisition->spare_parts_type = trim($request->spare_parts_type); 
            $purchaseRequisition->source_type = trim($request->source_type);
            $purchaseRequisition->item_safety_stock_qty = trim($request->item_safety_stock_qty);
            $purchaseRequisition->present_stock_qty = trim($request->present_stock_qty);
            $purchaseRequisition->required_qty = trim($request->required_qty);
            $purchaseRequisition->pipeline_qty = trim($request->pipeline_qty);
            $purchaseRequisition->total_value = $purchaseRequisition->item_avg_price * trim($request->required_qty);
            $purchaseRequisition->remarks = trim($request->remarks);
            $purchaseRequisition->updated_by = $request->user()->id;

            if($purchaseRequisition->save()) {
                $message = toastMessage('Purchase requisition has been successfully updated');
            }else {
                $message = toastMessage('Purchase requisition has not been updated', 'error');
            }

            session()->flash('toast', $message);

            return redirect('purchase-requisitions/list');
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
        if(PurchaseRequisition::whereRequisitionCode($request->hdnResource)->delete()) {
            $message = toastMessage('Purchase requisition has been successfully removed.','success');
        }else{
            $message = toastMessage('Purchase requisition has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }

    /**
     * Change approve status
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $requisition_code
     * @return \Illuminate\Http\Response
     */
    public function changeApproveStatus(Request $request, $requisition_code)
    {
        $purchaseRequisitions = PurchaseRequisition::whereRequisitionCode($requisition_code)->get();

        if($purchaseRequisitions->isEmpty()) {
            session()->flash('toast', toastMessage('Purchase requisition info not found!'));

            return back();
        }

        $purchaseRequisition = $purchaseRequisitions->first();
        $is_approved = $purchaseRequisition->approved_by == true;

        if($request->isMethod('POST')) {
            $total_purchase_requisition_id = count($request->purchase_requisition_id);
            $total_approved_qty = count($request->approved_qty);
            
            if($total_purchase_requisition_id == $total_approved_qty && $total_approved_qty) {
                
                for ($i=0; $i < $total_approved_qty; $i++) { 
                    $rules['purchase_requisition_id.'.$i] = 'required|integer|min:1';
                    $rules['approved_qty.'.$i] = 'required|integer|min:0';
                }

                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()) {
                    $messages = $validator->messages()->all();
                    $validation_error = '';

                    foreach($messages as $value) {
                        $validation_error .= $value . '<br/>';
                    }

                    session()->flash('toast', toastMessage($validation_error, 'error'));
                    return back();
                }

                $updated_item_number = 0;
                // update 
                for ($i=0; $i < $total_approved_qty; $i++) { 

                    $purchaseRequisition = $purchaseRequisitions->where('id', trim($request->purchase_requisition_id[$i]))->first();
                    
                    if($purchaseRequisition) {
                        
                        if(!$is_approved && trim($request->approved_qty[$i]) > $purchaseRequisition->required_qty) {
                            session()->flash('toast', toastMessage('The approve qty '. $i .' can not be greater than ' . $purchaseRequisition->required_qty, 'error'));

                            return back();
                        }

                        if(!$is_approved) {
                            $purchaseRequisition->approved_qty = $request->approved_qty[$i];
                            $purchaseRequisition->remaining_qty += $request->approved_qty[$i];
                            $purchaseRequisition->approved_by = $request->user()->id;
                            $purchaseRequisition->approved_date = date('Y-m-d');
                        }else {
                            $purchaseRequisition->approved_qty = 0;
                            $purchaseRequisition->remaining_qty = 0;
                            $purchaseRequisition->approved_by = 0;
                            $purchaseRequisition->approved_date = null;
                        }
                        
                        $purchaseRequisition->updated_by = $request->user()->id;

                        if($purchaseRequisition->save()) {
                            // update item pr qty
                            if($purchaseRequisition->approved_by) {
                                $item = $purchaseRequisition->item;
                                $item->pipeline_qty = $request->approved_qty[$i];
                                $item->save();
                            }
                            
                            $updated_item_number++;
                        }
                    }
                }

                if($updated_item_number) {
                    $message = toastMessage(' Purchase requisition information have been successfully ');
                }else {
                    $message = toastMessage(' Purchase requisition information have been successfully approved', 'error');
                }

                session()->flash('toast', $message);

                return redirect('purchase-requisitions/list');
            }

            session()->flash('toast', toastMessage('Please item(s) correctly'));

            return back();
        }

        return view('purchase-requisitions.approve', compact('purchaseRequisitions', 'purchaseRequisition'));
    }

    /**
     * return option tags of items based on requisition code
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $requisition_code
     * @return \Illuminate\Http\Response
     */
    public function fetchPrItems(Request $request, $requisition_code)
    {
        $items = PurchaseRequisition::getItemDropDownList(true, $requisition_code);

        if($items) {
            $options = '';

            foreach ($items as $key => $value) {
                $options .= '<option value="'.$key.'">'.$value.'</option>';
            }

            return $options;
        }
    }
}
