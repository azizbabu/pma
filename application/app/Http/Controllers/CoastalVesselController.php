<?php

namespace App\Http\Controllers;

use App\CoastalVessel;
use Illuminate\Http\Request;
use Validator;

class CoastalVesselController extends Controller
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
        $query = CoastalVessel::query();

        $data = [];

        if($request->has('search_item')) {
            $query->where('name', 'LIKE', '%'. trim($request->search_item) . '%')
                ->orWhere('code', 'LIKE', '%'. trim($request->search_item) . '%');

            $data['search_item'] = trim($request->search_item);
        }

        $coastalVessels = $query->latest()->paginate();

        $coastalVessels->paginationSummary = getPaginationSummary($coastalVessels->total(), $coastalVessels->perPage(), $coastalVessels->currentPage());

        if($data) {
            $coastalVessels->appends($data);
        }

        return view('coastal-vessels.index', compact('coastalVessels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = CoastalVessel::getCode();

        return view('coastal-vessels.create', compact('code'));
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

        if(!$request->has('coastal_vessel_id')) {
            $rules = $rules + [
                'code' => 'required|alpha_num|min:6|max:10|unique:coastal_vessels',
            ];
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $coastalVessel = !$request->has('coastal_vessel_id') ? new CoastalVessel : CoastalVessel::findOrFail($request->coastal_vessel_id);
            
            $coastalVessel->name = trim($request->name);

            if(!$request->has('coastal_vessel_id')) {
                $coastalVessel->code = trim($request->code);
            }

            $coastalVessel->address = trim($request->address);
            $coastalVessel->contact_person_name = trim($request->contact_person_name);
            $coastalVessel->contact_person_phone = trim($request->contact_person_phone);
            $coastalVessel->contact_person_email = trim($request->contact_person_email);
            
            if(!$request->has('coastal_vessel_id')) {
                $coastalVessel->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $coastalVessel->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($coastalVessel->save()) { 
                $message = toastMessage ( " Coastal vessel has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " Coastal vessel has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('coastal-vessels/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CoastalVessel  $coastalVessel
     * @return \Illuminate\Http\Response
     */
    public function show(CoastalVessel $coastalVessel)
    {
        return view('coastal-vessels.show', compact('coastalVessel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CoastalVessel  $coastalVessel
     * @return \Illuminate\Http\Response
     */
    public function edit(CoastalVessel $coastalVessel)
    {
        return view('coastal-vessels.edit', compact('coastalVessel'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(CoastalVessel::destroy($request->hdnResource)) {
            $message = toastMessage('Coastal vessel has been successfully removed.','success');
        }else{
            $message = toastMessage('Coastal vessel has not been removed.','error');
        }

        // Redirect
        session()->flash('toast', $message);
        
        return back();
    }
}
