<?php

namespace App\Http\Controllers;

use App\Tank;
use Illuminate\Http\Request;
use App\Terminal;
use Validator;

class TankController extends Controller
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
        $query = Tank::query();

        $data = [];

        if($request->filled('terminal_id')) {
            $query->where('terminal_id', trim($request->terminal_id));

            $data['terminal_id'] = trim($request->terminal_id);
        }

        if($request->filled('number')) {
            $query->where('number', 'LIKE', '%'. trim($request->number) . '%');

            $data['number'] = trim($request->number);
        }

        $tanks = $query->latest()->paginate();

        $tanks->paginationSummary = getPaginationSummary($tanks->total(), $tanks->perPage(), $tanks->currentPage());

        if($data) {
            $tanks->appends($data);
        }

        $terminals = Terminal::getDropDownList();

        return view('tanks.index', compact('tanks', 'terminals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $terminals = Terminal::getDropDownList();
        $number = Tank::getCode();

        return view('tanks.create', compact('terminals', 'number'));
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
            'terminal_id'  => 'required|integer|min:1',
            'capacity'  => 'required|numeric|min:0',
        ];

        if(!$request->has('tank_id')) {
            $rules = $rules + [
                'number' => 'required|alpha_num|min:6|max:10|unique:tanks',
            ];
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $tank = !$request->has('tank_id') ? new Tank : Tank::findOrFail($request->tank_id);
            
            $tank->terminal_id = trim($request->terminal_id);

            if(!$request->has('tank_id')) {
                $tank->number = trim($request->number);
            }

            $tank->capacity = trim($request->capacity);
            
            if(!$request->has('tank_id')) {
                $tank->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $tank->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($tank->save()) { 
                $message = toastMessage ( " Tank has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " Tank has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('tanks/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tank  $tank
     * @return \Illuminate\Http\Response
     */
    public function show(Tank $tank)
    {
        return view('tanks.show', compact('tank'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tank  $tank
     * @return \Illuminate\Http\Response
     */
    public function edit(Tank $tank)
    {
        $terminals = Terminal::getDropDownList();

        return view('tanks.edit', compact('terminals', 'tank'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(Tank::destroy($request->hdnResource)) {
            $message = toastMessage('Tank has been successfully removed.','success');
        }else{
            $message = toastMessage('Tank has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }
}
