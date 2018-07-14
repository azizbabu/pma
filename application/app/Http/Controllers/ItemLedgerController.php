<?php

namespace App\Http\Controllers;

use App\ItemLedger;
use Illuminate\Http\Request;
use App\Item;
use App\Plant;
use Carbon, Validator;

class ItemLedgerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission')->except(['getList', 'changeApproveStatus']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = ItemLedger::query();

        $data = [];

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));
                
            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('item_id')) {
            $query->where('item_id', trim($request->item_id));
                
            $data['item_id'] = trim($request->item_id);
        }

        if($request->filled('issue_code')) {
            $query->where('issue_code', 'LIKE', '%'. trim($request->issue_code) . '%');

            $data['issue_code'] = trim($request->issue_code);
        }

        if($request->filled('issue_date')) {
            $query->where('issue_date', trim($request->issue_date));
                
            $data['issue_date'] = trim($request->issue_date);
        }

        $itemLedgers = $query->latest()->paginate();

        $itemLedgers->paginationSummary = getPaginationSummary($itemLedgers->total(), $itemLedgers->perPage(), $itemLedgers->currentPage());

        if($data) {
            $itemLedgers->appends($data);
        }

        $plants = Plant::getDropDownlist();
        $items = Item::getDropDownlist();

        return view('item-ledgers.index', compact('itemLedgers', 'plants', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $issue_code = ItemLedger::getIssueCode();
        $plants = Plant::getDropDownlist();
        $items = Item::getDropDownlist();

        return view('item-ledgers.create', compact('issue_code', 'plants', 'items'));
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
            'issue_date'  => 'required|date|date_format:Y-m-d',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'status'    => 400,
                'error' => $validator->errors(),
            ]);
        }else{
            $total_item = count($request->item_id);
            $total_receive_qty = count($request->receive_qty);
            $total_issue_qty = count($request->issue_qty);

            if($total_item == $total_receive_qty && $total_receive_qty == $total_issue_qty && $total_issue_qty) {
                
                for ($i=0; $i < $total_item; $i++) { 
                    $rules['item_id.'.$i] = 'required|integer|min:1';
                    $rules['receive_qty.'.$i] = 'required|integer|min:0';
                    $rules['issue_qty.'.$i] = 'required|integer|min:0';
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

                $issue_code = ItemLedger::getIssueCode();
                $insert_item_number = 0;

                // insert 
                for ($i=0; $i < $total_item; $i++) { 

                    $item = Item::find($request->item_id[$i]);

                    if($item) {
                        $itemLedger = new ItemLedger;
                        $itemLedger->plant_id = trim($request->plant_id);
                        $itemLedger->issue_code = $issue_code;
                        $itemLedger->issue_date = trim($request->issue_date); 
                        $itemLedger->item_id = $item->id;
                        $itemLedger->receive_qty = $request->receive_qty[$i];
                        $itemLedger->issue_qty = $request->issue_qty[$i];
                        $itemLedger->remarks = $request->remarks[$i];
                        $itemLedger->created_by = $request->user()->id;

                        if($itemLedger->save()) {
                            $insert_item_number++;
                        }
                    }
                }
                
                if($insert_item_number) {
                    return response()->json([
                        'type'  => 'success',
                        'message'   => 'Item ledger information has been successfully added.',
                    ]);
                }else {
                    return response()->json([
                        'type'  => 'error',
                        'message'   => ' No item ledger added.',
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
     * @param  \App\ItemLedger  $itemLedger
     * @return \Illuminate\Http\Response
     */
    public function show(ItemLedger $itemLedger)
    {
        return view('item-ledgers.show', compact('itemLedger'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ItemLedger  $itemLedger
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemLedger $itemLedger)
    {
        return view('item-ledgers.edit', compact('itemLedger'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ItemLedger  $itemLedger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemLedger $itemLedger)
    {
        $rules = [
            'receive_qty'   => 'required|integer|min:0',
            'issue_qty'   => 'required|integer|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }else {
            // update
            $itemLedger->receive_qty = trim($request->receive_qty);
            $itemLedger->issue_qty = trim($request->issue_qty);
            $itemLedger->remarks = trim($request->remarks);
            $itemLedger->updated_by = $request->user()->id;

            if($itemLedger->save()) {
                $message = toastMessage('Item ledger has been successfully updated');
            }else {
                $message = toastMessage('Item ledger has not been updated', 'error');
            }

            session()->flash('toast', $message);

            return redirect('item-ledgers/list');
        }
    }

    /**
     * Change approve status
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeApproveStatus(Request $request)
    {
        $itemLedger = ItemLedger::findOrFail($request->hdnResource);

        if(!$itemLedger->approved_by) {
            $itemLedger->approved_by = $request->user()->id;
            $itemLedger->approved_date = date('Y-m-d');
            $msg = 'approved';
        }else {
            $itemLedger->approved_by = 0;
            $itemLedger->approved_date = null;
            $msg = 'unapproved';
        }

        if($itemLedger->save()) {
            $message = toastMessage('Item ledger has been '. $msg);
        }else {
            $message = toastMessage('Item ledger has not been '. $msg, 'error');
        }

        session()->flash('toast', $message);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(ItemLedger::destroy($request->hdnResource)) {
            $message = toastMessage('Item ledger has been successfully removed.','success');
        }else{
            $message = toastMessage('Item ledger has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }
}
