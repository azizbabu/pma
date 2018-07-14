<?php

namespace App\Http\Controllers;

use App\Engine;
use Illuminate\Http\Request;
use App\Plant;
use Validator;

class EngineController extends Controller
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
        $query = Engine::query();

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

        $engines = $query->latest()->paginate();

        $engines->paginationSummary = getPaginationSummary($engines->total(), $engines->perPage(), $engines->currentPage());

        if($data) {
            $engines->appends($data);
        }

        $plants = Plant::getDropDownList();

        return view('engines.index', compact('engines', 'plants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $plants = Plant::getDropDownList();
        $number = Engine::getNumber();

        return view('engines.create', compact('plants', 'number'));
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

        if(!$request->has('engine_id')) {
            $rules = $rules + [
                'number' => 'required|alpha_num|min:6|max:10|unique:engines',
            ];
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $engine = !$request->has('engine_id') ? new Engine : Engine::findOrFail($request->engine_id);
            
            $engine->plant_id = trim($request->plant_id);
            $engine->name = trim($request->name);

            if(!$request->has('engine_id')) {
                $engine->number = trim($request->number);
            }

            $engine->capacity = trim($request->capacity);
            $engine->unit = trim($request->unit);
            
            if(!$request->has('engine_id')) {
                $engine->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $engine->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($engine->save()) { 
                $message = toastMessage ( " Engine has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " Engine has not been $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('engines/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Engine  $engine
     * @return \Illuminate\Http\Response
     */
    public function show(Engine $engine)
    {
        return view('engines.show', compact('engine'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Engine  $engine
     * @return \Illuminate\Http\Response
     */
    public function edit(Engine $engine)
    {
        $plants = Plant::getDropDownList();

        return view('engines.edit', compact('engine', 'plants'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(Engine::destroy($request->hdnResource)) {
            $message = toastMessage('Engine has been successfully removed.', 'success');
        }else{
            $message = toastMessage('Engine has not been removed.','error');
        }

        // redirect
        session()->flash('toast', $message);
        
        return back();
    }
}
