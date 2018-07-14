<?php

namespace App\Http\Controllers;

use App\MotherVessel;
use Illuminate\Http\Request;
use Validator;

class MotherVesselController extends Controller
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
        $query = MotherVessel::query();

        $data = [];

        if($request->has('search_item')) {
            $query->where('name', 'LIKE', '%'. trim($request->search_item) . '%')
                ->orWhere('code', 'LIKE', '%'. trim($request->search_item) . '%');

            $data['search_item'] = trim($request->search_item);
        }

        $mother_vessels = $query->latest()->paginate();

        $mother_vessels->paginationSummary = getPaginationSummary($mother_vessels->total(), $mother_vessels->perPage(), $mother_vessels->currentPage());

        if($data) {
            $mother_vessels->appends($data);
        }

        return view('mother-vessels.index', compact('mother_vessels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = MotherVessel::getCode();

        return view('mother-vessels.create', compact('code'));
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

        if(!$request->has('mother_vessel_id')) {
            $rules = $rules + [
                'code' => 'required|alpha_num|min:6|max:10|unique:mother_vessels',
            ];
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $mother_vessel = !$request->has('mother_vessel_id') ? new MotherVessel : MotherVessel::findOrFail($request->mother_vessel_id);
            
            $mother_vessel->name = trim($request->name);

            if(!$request->has('mother_vessel_id')) {
                $mother_vessel->code = trim($request->code);
            }

            $mother_vessel->address = trim($request->address);
            $mother_vessel->contact_person_name = trim($request->contact_person_name);
            $mother_vessel->contact_person_phone = trim($request->contact_person_phone);
            $mother_vessel->contact_person_email = trim($request->contact_person_email);
            
            if(!$request->has('mother_vessel_id')) {
                $mother_vessel->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $mother_vessel->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($mother_vessel->save()) { 
                $message = toastMessage ( " Mother vessel has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " Mother vessel has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('mother-vessels/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MotherVessel  $motherVessel
     * @return \Illuminate\Http\Response
     */
    public function show(MotherVessel $motherVessel)
    {
        return view('mother-vessels.show', compact('motherVessel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MotherVessel  $motherVessel
     * @return \Illuminate\Http\Response
     */
    public function edit(MotherVessel $motherVessel)
    {
        return view('mother-vessels.edit', compact('motherVessel'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(MotherVessel::destroy($request->hdnResource)) {
            $message = toastMessage('Mother vessel has been successfully removed.','success');
        }else{
            $message = toastMessage('Mother vessel has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }
}
