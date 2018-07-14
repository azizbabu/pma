<?php

namespace App\Http\Controllers;

use App\Terminal;
use Illuminate\Http\Request;
use Validator;

class TerminalController extends Controller
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
        $query = Terminal::query();

        $data = [];

        if($request->has('search_item')) {
            $query->where('name', 'LIKE', '%'. trim($request->search_item) . '%')
                ->orWhere('code', 'LIKE', '%'. trim($request->search_item) . '%');

            $data['search_item'] = trim($request->search_item);
        }

        $terminals = $query->latest()->paginate();

        $terminals->paginationSummary = getPaginationSummary($terminals->total(), $terminals->perPage(), $terminals->currentPage());

        if($data) {
            $terminals->appends($data);
        }

        return view('terminals.index', compact('terminals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = Terminal::getCode();

        return view('terminals.create', compact('code'));
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
            'capacity'  => 'required|numeric|min:0',
        ];

        if(!$request->has('terminal_id')) {
            $rules = $rules + [
                'code' => 'required|alpha_num|min:6|max:10|unique:terminals',
            ];
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $terminal = !$request->has('terminal_id') ? new Terminal : Terminal::findOrFail($request->terminal_id);
            
            $terminal->name = trim($request->name);

            if(!$request->has('terminal_id')) {
                $terminal->code = trim($request->code);
            }

            $terminal->address = trim($request->address);
            $terminal->capacity = trim($request->capacity);
            $terminal->manager_name = trim($request->manager_name);
            $terminal->manager_phone = trim($request->manager_phone);
            $terminal->manager_email = trim($request->manager_email);
            
            if(!$request->has('terminal_id')) {
                $terminal->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $terminal->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($terminal->save()) { 
                $message = toastMessage ( " Terminal has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " Terminal has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('terminals/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Terminal  $terminal
     * @return \Illuminate\Http\Response
     */
    public function show(Terminal $terminal)
    {
        return view('terminals.show', compact('terminal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Terminal  $terminal
     * @return \Illuminate\Http\Response
     */
    public function edit(Terminal $terminal)
    {
        return view('terminals.edit', compact('terminal'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(Terminal::destroy($request->hdnResource)) {
            $message = toastMessage('Terminal has been successfully removed.','success');
        }else{
            $message = toastMessage('Terminal has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }
}
