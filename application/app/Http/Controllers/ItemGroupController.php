<?php

namespace App\Http\Controllers;

use App\ItemGroup;
use Illuminate\Http\Request;
use Validator;

class ItemGroupController extends Controller
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
        $query = ItemGroup::query();

        $data = [];

        if($request->has('search_item')) {
            $query->where('name', 'LIKE', '%'. trim($request->search_item) . '%')
                ->orWhere('code', 'LIKE', '%'. trim($request->search_item) . '%');

            $data['search_item'] = trim($request->search_item);
        }

        $itemGroups = $query->latest()->paginate();

        $itemGroups->paginationSummary = getPaginationSummary($itemGroups->total(), $itemGroups->perPage(), $itemGroups->currentPage());

        if($data) {
            $itemGroups->appends($data);
        }

        return view('item-groups.index', compact('itemGroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = ItemGroup::getCode();

        return view('item-groups.create', compact('code'));
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
            'name'  => 'required|string|max:255',
        ];

        if(!$request->has('item_group_id')) {
            $rules = $rules + [
                'code' => 'required|alpha_num|min:6|max:10|unique:item_groups',
            ];
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $itemGroup = !$request->has('item_group_id') ? new ItemGroup : ItemGroup::findOrFail($request->item_group_id);
            
            $itemGroup->name = trim($request->name);

            if(!$request->has('item_group_id')) {
                $itemGroup->code = trim($request->code);
            }
            
            if(!$request->has('item_group_id')) {
                $itemGroup->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $itemGroup->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($itemGroup->save()) { 
                $message = toastMessage ( " Item group has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " Item group has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('item-groups/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ItemGroup  $itemGroup
     * @return \Illuminate\Http\Response
     */
    public function show(ItemGroup $itemGroup)
    {
        return view('item-groups.show', compact('itemGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ItemGroup  $itemGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemGroup $itemGroup)
    {
        return view('item-groups.edit', compact('itemGroup'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(ItemGroup::destroy($request->hdnResource)) {
            $message = toastMessage('Item group has been successfully removed.','success');
        }else{
            $message = toastMessage('Item group has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }
}
