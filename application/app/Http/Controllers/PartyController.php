<?php

namespace App\Http\Controllers;

use App\Party;
use Illuminate\Http\Request;
use Validator;

class PartyController extends Controller
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
        $query = Party::query();

        $data = [];

        if($request->has('search_item')) {
            $query->where('name', 'LIKE', '%'. trim($request->search_item) . '%')
                ->orWhere('code', 'LIKE', '%'. trim($request->search_item) . '%');

            $data['search_item'] = trim($request->search_item);
        }

        $parties = $query->latest()->paginate();

        $parties->paginationSummary = getPaginationSummary($parties->total(), $parties->perPage(), $parties->currentPage());

        if($data) {
            $parties->appends($data);
        }

        return view('parties.index', compact('parties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = Party::getCode();

        return view('parties.create', compact('code'));
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

        if(!$request->has('party_id')) {
            $rules = $rules + [
                'code' => 'required|alpha_num|min:6|max:10|unique:parties',
            ];
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $party = !$request->has('party_id') ? new Party : Party::findOrFail($request->party_id);
            
            $party->name = trim($request->name);

            if(!$request->has('party_id')) {
                $party->code = trim($request->code);
            }

            $party->address = trim($request->address);
            $party->mobile = trim($request->mobile);
            $party->email = trim($request->email);
            $party->contact_person_name = trim($request->contact_person_name);
            $party->contact_person_mobile = trim($request->contact_person_mobile);
            $party->contact_person_email = trim($request->contact_person_email);
            
            if(!$request->has('party_id')) {
                $party->created_by = $request->user()->id;
                $msg = 'added';
            }else {
                $party->updated_by = $request->user()->id;
                $msg = 'updated';
            }

            if($party->save()) { 
                $message = toastMessage( " Party has been successfully $msg", 'success' );

            }else{
                $message = toastMessage( " Party has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('parties/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function show(Party $party)
    {
        return view('parties.show', compact('party'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function edit(Party $party)
    {
        return view('parties.edit', compact('party'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(Party::destroy($request->hdnResource)) {
            $message = toastMessage('Party has been successfully removed.','success');
        }else{
            $message = toastMessage('Party has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }
}
