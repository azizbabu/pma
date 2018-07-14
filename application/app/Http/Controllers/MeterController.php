<?php

namespace App\Http\Controllers;

use App\Meter;
use Illuminate\Http\Request;
use App\Plant;
use Validator;

class MeterController extends Controller
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
        $query = Meter::query();

        $data = [];

        if($request->filled('plant_id')) {
            $query->where('plant_id', trim($request->plant_id));
                
            $data['plant_id'] = trim($request->plant_id);
        }

        if($request->filled('search_item')) {
            $query->where('name', 'LIKE', '%'. trim($request->search_item) . '%')
                ->orWhere('number', 'LIKE', '%'. trim($request->search_item) . '%');

            $data['search_item'] = trim($request->search_item);
        }

        $meters = $query->latest()->paginate();

        $meters->paginationSummary = getPaginationSummary($meters->total(), $meters->perPage(), $meters->currentPage());

        if($data) {
            $meters->appends($data);
        }

        $plants = Plant::getDropDownList();

        return view('meters.index', compact('meters', 'plants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $plants = Plant::getDropDownList();
        $number = Meter::getNumber();

        return view('meters.create', compact('plants', 'number'));
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
            'name'      => 'required|string|max:255',
            'capacity'  => 'required|numeric|min:0',
            'unit'  => 'required|string|max:20',
        ];

        if(!$request->has('meter_id')) {
            $rules = $rules + [
                'number' => 'required|alpha_num|min:6|max:10|unique:meters',
            ];
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $engine = !$request->has('meter_id') ? new Meter : Meter::findOrFail($request->meter_id);
            
            $engine->plant_id = trim($request->plant_id);
            $engine->name = trim($request->name);

            if(!$request->has('meter_id')) {
                $engine->number = trim($request->number);
            }

            $engine->capacity = trim($request->capacity);
            $engine->unit = trim($request->unit);
            
            if(!$request->has('meter_id')) {
                $engine->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $engine->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($engine->save()) { 
                $message = toastMessage ( " Meter has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " Meter has not been $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('meters/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Meter  $meter
     * @return \Illuminate\Http\Response
     */
    public function show(Meter $meter)
    {
        return view('meters.show', compact('meter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Meter  $meter
     * @return \Illuminate\Http\Response
     */
    public function edit(Meter $meter)
    {
        $plants = Plant::getDropDownList();

        return view('meters.edit', compact('meter', 'plants'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(Meter::destroy($request->hdnResource)) {
            $message = toastMessage('Meter has been successfully removed.', 'success');
        }else{
            $message = toastMessage('Meter has not been removed.','error');
        }

        // redirect
        session()->flash('toast', $message);
        
        return back();
    }
}
