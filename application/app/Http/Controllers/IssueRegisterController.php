<?php

namespace App\Http\Controllers;

use App\IssueRegister;
use Illuminate\Http\Request;
use App\Item;
use App\Plant;
use Carbon, DB, Validator;

class IssueRegisterController extends Controller
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
    public function getListOld(Request $request)
    {
        $query = IssueRegister::query();

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

        $issueRegisters = $query->latest()->paginate();

        $issueRegisters->paginationSummary = getPaginationSummary($issueRegisters->total(), $issueRegisters->perPage(), $issueRegisters->currentPage());

        if($data) {
            $issueRegisters->appends($data);
        }

        $plants = Plant::getDropDownlist();
        $items = Item::getDropDownlist();

        return view('issue-registers.index-old', compact('issueRegisters', 'plants', 'items'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = IssueRegister::select(
            'issue_code',
            'issue_date',
            DB::raw('COUNT(item_id) AS item_qty'),
            DB::raw('SUM(required_qty) AS req_qty'),
            DB::raw('SUM(approved_qty) as apv_qty'),
            DB::raw('SUM(issue_qty) as issue_qty')
        );

        $data = [];

        if($request->filled('issue_code')) {
            $query->where('issue_code', 'LIKE', '%'. trim($request->issue_code) . '%');

            $data['issue_code'] = trim($request->issue_code);
        }

        if($request->filled('issue_date')) {
            $query->where('issue_date', trim($request->issue_date));
                
            $data['issue_date'] = trim($request->issue_date);
        }

        $issueRegisters = $query->groupBy('issue_code')->latest()->paginate();

        $issueRegisters->paginationSummary = getPaginationSummary($issueRegisters->total(), $issueRegisters->perPage(), $issueRegisters->currentPage());

        if($data) {
            $issueRegisters->appends($data);
        }

        return view('issue-registers.index', compact('issueRegisters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $issue_code = IssueRegister::getIssueCode();
        $plants = Plant::getDropDownlist();

        return view('issue-registers.create', compact('issue_code', 'plants'));
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

        $total_item = count($request->item_id);
        $total_item_safety_stock_qty = count($request->item_safety_stock_qty);
        $total_balance_stock_qty = count($request->balance_stock_qty);
        $total_required_qty = count($request->required_qty);
        $total_issue_qty = count($request->issue_qty);

        if($total_item == $total_item_safety_stock_qty && $total_item_safety_stock_qty == $total_balance_stock_qty && $total_balance_stock_qty == $total_required_qty && $total_required_qty == $total_issue_qty && $total_issue_qty) {
            
            for ($i=0; $i < $total_item; $i++) { 
                $rules['item_id.'.$i] = 'required|integer|min:1';
                $rules['item_safety_stock_qty.'.$i] = 'required|integer|min:1';
                $rules['balance_stock_qty.'.$i] = 'required|integer|min:0';
                $rules['required_qty.'.$i] = 'required|integer|min:1';
                $rules['issue_qty.'.$i] = 'required|integer|min:1';
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

            // Delete Issue register if requisition code already exists
            if($request->has('issue_code')) {
                $issueRegisters = IssueRegister::whereIssueCode(trim($request->issue_code))->get();

                if($issueRegisters->isNotEmpty()) {
                    // Update issue qty and issue approval qty of item 
                    foreach($issueRegisters as $issueRegister) {
                        $item = $issueRegister->item;
                        $item->issue_qty = $item->issue_qty - $issueRegister->issue_qty;

                        if($issueRegister->approved_qty) {
                            $item->issue_approval_qty = $item->issue_approval_qty - $issueRegister->approved_qty;
                        }
                        $item->save();
                    }

                    IssueRegister::whereIssueCode(trim($request->issue_code))->forceDelete();

                    $issue_code = trim($request->issue_code);
                }
            }

            $issue_code = !empty($issue_code) ? $issue_code : IssueRegister::getIssueCode();
            $insert_item_number = 0;

            // insert 
            for ($i=0; $i < $total_item; $i++) { 

                $item = Item::find($request->item_id[$i]);

                if($item) {
                    
                    $issueRegister = new IssueRegister;
                    $issueRegister->plant_id = trim($request->plant_id);
                    $issueRegister->issue_code = $issue_code;
                    $issueRegister->issue_date = trim($request->issue_date); 
                    $issueRegister->spare_parts_type = trim($request->spare_parts_type); 
                    $issueRegister->source_type = trim($request->source_type);

                    $issueRegister->item_id = $item->id;
                    $issueRegister->item_avg_price = $item->avg_price;
                    $issueRegister->item_safety_stock_qty = $request->item_safety_stock_qty[$i];
                    $issueRegister->balance_stock_qty = $request->balance_stock_qty[$i];
                    $issueRegister->required_qty = $request->required_qty[$i];
                    $issueRegister->issue_qty = $request->issue_qty[$i];
                    $issueRegister->remarks = $request->remarks[$i];
                    $issueRegister->created_by = $request->user()->id;

                    if($request->has('issue_code')) {
                        $issueRegister->updated_by = $request->user()->id;
                        $msg = 'updated.';
                    }else {
                        $msg = 'updated.';
                    }

                    if($issueRegister->save()) {
                        // update issue qty of item
                        $item->issue_qty = $item->issue_qty + $request->issue_qty[$i];
                        $item->save();

                        $insert_item_number++;
                    }
                }
            }
            
            if($insert_item_number) {
                return response()->json([
                    'type'  => 'success',
                    'message'   => ' Issue Register information have been successfully '. $msg,
                ]);
            }

            return response()->json([
                'type'  => 'error',
                'message'   => 'Issue register information has not been '. $msg,
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
     * @param  string $issue_code
     * @return \Illuminate\Http\Response
     */
    public function show($issue_code)
    {
        $issueRegisters = IssueRegister::whereIssueCode($issue_code)->get();

        if($issueRegisters->isEmpty()) {
            session()->flash('toast', toastMessage('Issue register not found!', 'error'));

            return back();
        }
        
        $issueRegister = $issueRegisters->first();

        return view('issue-registers.show', compact('issueRegisters', 'issueRegister'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $issue_code
     * @return \Illuminate\Http\Response
     */
    public function edit($issue_code)
    {
        $issueRegisters = IssueRegister::whereIssueCode($issue_code)->get();

        if($issueRegisters->isEmpty()) {
            session()->flash('toast', toastMessage('Issue register not found!', 'error'));

            return back();
        }
        
        $issueRegister = $issueRegisters->first();
        $issue_code = $issueRegister->issue_code;
        $plants = Plant::getDropDownlist();

        return view('issue-registers.edit', compact('issueRegisters', 'issueRegister', 'issue_code', 'plants'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(IssueRegister::whereIssueCode($request->hdnResource)->delete()) {
            $message = toastMessage('Issue register has been successfully removed.','success');
        }else{
            $message = toastMessage('Issue register has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }

    /**
     * Change approve status
     *
     * @param \Illuminate\Http\Request $request
     * @param  string $issue_code
     * @return \Illuminate\Http\Response
     */
    public function changeApproveStatus(Request $request, $issue_code)
    {
        $issueRegisters = IssueRegister::whereIssueCode($issue_code)->get();

        if($issueRegisters->isEmpty()) {
            session()->flash('toast', toastMessage('Issue register not found!', 'error'));

            return back();
        }
        
        $issueRegister = $issueRegisters->first();
        $is_approved = $issueRegister->approved_by == true;

        if($request->isMethod('POST')) {
            $total_issue_register_id = count($request->issue_register_id);
            $total_approved_qty = count($request->approved_qty);
            
            if($total_issue_register_id == $total_approved_qty && $total_approved_qty) {
                
                for ($i=0; $i < $total_approved_qty; $i++) { 
                    $rules['issue_register_id.'.$i] = 'required|integer|min:1';
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

                    $issueRegister = $issueRegisters->where('id', trim($request->issue_register_id[$i]))->first();
                    
                    if($issueRegister) {
                        
                        if(!$is_approved && trim($request->approved_qty[$i]) > $issueRegister->required_qty) {
                            session()->flash('toast', toastMessage('The approve qty '. $i .' can not be greater than ' . $issueRegister->required_qty, 'error'));

                            return back();
                        }

                        if(!$is_approved) {
                            $approved_qty = $request->approved_qty[$i];
                            $issueRegister->approved_qty = $request->approved_qty[$i];
                            $issueRegister->approved_by = $request->user()->id;
                            $issueRegister->approved_date = date('Y-m-d');
                        }else {
                            $approved_qty = $issueRegister->approved_qty;
                            $issueRegister->approved_qty = 0;
                            $issueRegister->approved_by = 0;
                            $issueRegister->approved_date = null;
                        }
                        
                        $issueRegister->updated_by = $request->user()->id;

                        if($issueRegister->save()) {
                            // update item issue approval qty
                            $item = $issueRegister->item;
                            if($issueRegister->approved_by) {
                                $item->issue_approval_qty += $approved_qty;
                            }else {
                                $item->issue_approval_qty -= $approved_qty;
                            }
                            $item->save();
                            
                            $updated_item_number++;
                        }
                    }
                }

                if($updated_item_number) {
                    $message = toastMessage(' Issue register information have been successfully ');
                }else {
                    $message = toastMessage(' Issue register information have been successfully approved', 'error');
                }

                session()->flash('toast', $message);

                return redirect('issue-registers/list');
            }

            session()->flash('toast', toastMessage('Please item(s) correctly'));

            return back();
        }

        return view('issue-registers.approve', compact('issueRegisters', 'issueRegister'));
    }
}
