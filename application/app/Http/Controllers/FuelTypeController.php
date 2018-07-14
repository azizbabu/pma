<?php

namespace App\Http\Controllers;

use App\FuelType;
use Illuminate\Http\Request;
use Validator;

class FuelTypeController extends Controller
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
        $query = FuelType::query();

        $data = [];

        if($request->has('search_item')) {
            $query->where('name', 'LIKE', '%'. trim($request->search_item) . '%')
                ->orWhere('code', 'LIKE', '%'. trim($request->search_item) . '%');

            $data['search_item'] = trim($request->search_item);
        }

        $fuel_types = $query->latest()->paginate();

        $fuel_types->paginationSummary = getPaginationSummary($fuel_types->total(), $fuel_types->perPage(), $fuel_types->currentPage());

        if($data) {
            $fuel_types->appends($data);
        }

        return view('fuel-types.index', compact('fuel_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = FuelType::getCode();

        return view('fuel-types.create', compact('code'));
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
            'unit'  => 'required|string|max:20',
        ];

        if(!$request->has('fuel_type_id')) {
            $rules = $rules + [
                'code' => 'required|alpha_num|min:6|max:10|unique:fuel_types',
            ];
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $fuel_type = !$request->has('fuel_type_id') ? new FuelType : FuelType::findOrFail($request->fuel_type_id);
            
            $fuel_type->name = trim($request->name);

            if(!$request->has('fuel_type_id')) {
                $fuel_type->code = trim($request->code);
            }
            $fuel_type->unit = trim($request->unit);
            
            if(!$request->has('fuel_type_id')) {
                $fuel_type->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $fuel_type->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($fuel_type->save()) { 
                $message = toastMessage ( " Fuel type has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " Fuel type has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('fuel-types/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FuelType  $fuelType
     * @return \Illuminate\Http\Response
     */
    public function show(FuelType $fuelType)
    {
        return view('fuel-types.show', compact('fuelType'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FuelType  $fuelType
     * @return \Illuminate\Http\Response
     */
    public function edit(FuelType $fuelType)
    {
        return view('fuel-types.edit', compact('fuelType'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(FuelType::destroy($request->hdnResource)) {
            $message = toastMessage('Fuel type has been successfully removed.','success');
        }else{
            $message = toastMessage('Fuel type has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }
}
