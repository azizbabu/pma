<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use App\ItemGroup;
use App\Plant;
use Validator;

class ItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission')->except('getList', 'fetchItem', 'fetchItems');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = Item::query();

        $data = [];

        if($request->filled('item_group_id')) {
            $query->where('item_group_id', trim($request->item_group_id));

            $data['item_group_id'] = trim($request->item_group_id);
        }

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));

            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('search_item')) {
            $query->where('name', 'LIKE', '%'. trim($request->search_item) . '%')
                ->orWhere('code', 'LIKE', '%'. trim($request->search_item) . '%');

            $data['search_item'] = trim($request->search_item);
        }

        $items = $query->latest()->paginate();

        $items->paginationSummary = getPaginationSummary($items->total(), $items->perPage(), $items->currentPage());

        if($data) {
            $items->appends($data);
        }

        $itemGroups = ItemGroup::getDropDownList();
        $plants = Plant::getDropDownList();

        return view('items.index', compact('items', 'itemGroups', 'plants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = Item::getCode();
        $pr_number = Item::getPrNumber();
        $itemGroups = ItemGroup::getDropDownList();
        $plants = Plant::getDropDownList();

        return view('items.create', compact('code', 'pr_number', 'itemGroups', 'plants'));
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
            'item_group_id' => 'required|integer|min:1',
            'plant_id' => 'required|integer|min:1',
            'name'  => 'required|string|max:255',
            'pr_number' => 'required|alpha_num|min:6|max:10|unique:items,pr_number'.($request->has('item_id') ? ',' . trim($request->item_id) : ''),
            'source_type'  => 'required|string|max:20',
            'stock_type'  => 'required|string|max:20',
            'avg_price' => 'required|numeric|min:1',
        ];

        if(!$request->has('item_id')) {
            $rules = $rules + [
                'code' => 'required|alpha_num|min:6|max:10|unique:items',
            ];
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $item = !$request->has('item_id') ? new Item : Item::findOrFail($request->item_id);
            
            $item->item_group_id = trim($request->item_group_id);
            $item->plant_id = trim($request->plant_id);
            $item->name = trim($request->name);

            if(!$request->has('item_id')) {
                $item->code = trim($request->code);
            }
            $item->pr_number = trim($request->pr_number);
            $item->source_type = trim($request->source_type);
            $item->stock_type = trim($request->stock_type);
            $item->opening_qty = trim($request->opening_qty);
            $item->avg_price = trim($request->avg_price);
            $item->safety_stock_qty = trim($request->safety_stock_qty);
            $item->remarks = trim($request->remarks);
            
            if(!$request->has('item_id')) {
                $item->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $item->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($item->save()) { 
                $message = toastMessage ( " Item has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " Item has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('items/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $itemGroups = ItemGroup::getDropDownList();
        $plants = Plant::getDropDownList();

        return view('items.edit', compact('item', 'itemGroups', 'plants'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(Item::destroy($request->hdnResource)) {
            $message = toastMessage('Item has been successfully removed.','success');
        }else{
            $message = toastMessage('Item has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
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
        $item = Item::find($id);

        if(!$item) {
            return response()->json([
                'type'  => 'error',
                'message'   => 'Item not found!',
            ]);
        }
        $item->balance_stock_qty = $item->getBalanceStockQty();

        return json_encode($item);
    }

    /**
     * Get item list based on plant id
     *
     * @param  \Illuminate\Http\Request $request
     * @param int $plant_id
     * @return \Illuminate\Http\Response
     */
    public function fetchItems(Request $request, $plant_id)
    {
        $items = Item::getDropDownList(true, $plant_id);

        if($items) {
            $options = '';
            
            foreach($items as $key=>$value) {
                $options .= '<option value="'.$key.'">'.$value.'</option>';
            }

            return $options;
        }
    }
}
