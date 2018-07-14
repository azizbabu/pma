<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Role;
use Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission')->except('getList', 'profile');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = User::query();

        $data = [];

        if($request->has('search_item')) {
            $query->where('name', 'LIKE', '%'. trim($request->search_item) . '%')
                ->orWhere('email', 'LIKE', '%'. trim($request->search_item) . '%')
                ->orWhere('phone', 'LIKE', '%'. trim($request->search_item) . '%');

            $data['search_item'] = trim($request->search_item);
        }

        $users = $query->latest()->paginate();

        $users->paginationSummary = getPaginationSummary($users->total(), $users->perPage(), $users->currentPage());

        if($data) {
            $users->appends($data);
        }

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::getDropDownList(true, 1);

        return view('users.create', compact('roles'));
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
            'role_id'  => 'required|integer',
            'name' => 'required|string|max:255',
        ];

        if(!$request->has('user_id')) {
            $rules = $rules + [
                'username' => 'required|string|alpha_dash|max:100|unique:users',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ];
        }else {
            if($request->filled('password')) {
                $rules = $rules + [
                    'password' => 'string|min:6|confirmed',
                ];
            }
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // store or update
            $user = !$request->has('user_id') ? new User : User::findOrFail($request->user_id);
            
            $user->role_id = trim($request->role_id);
            $user->name = trim($request->name);
            $user->phone = trim($request->phone);

            if(!$request->has('user_id')) {
                $user->username = trim($request->username);
                $user->email = trim($request->email);
            }
            
            if($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            
            if(!$request->has('user_id')) {
                $msg = 'added';
            }else {
                $msg = 'updated';
            }

            if($user->save()) { 
                $message = toastMessage ( " User has been successfully $msg", 'success' );

            }else{
                $message = toastMessage ( " User has not been successfully $msg", 'error' );
            }

            // redirect
            session()->flash('toast', $message);
            
            return redirect('users/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::getDropDownList(true, 1);

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(User::destroy($request->hdnResource)) {
            $message = toastMessage('User has been successfully removed.','success');
        }else{
            $message = toastMessage('User has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }

    /**
     * Display user profile form and update profile info
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        if($request->isMethod('POST')) {
            $rules = [
                'name' => 'required|string|max:255',
            ];

            if($request->filled('password')) {
                $rules = $rules + [
                    'password' => 'string|min:6|confirmed',
                ];
            }

            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                // store or update
                $user = !$request->has('user_id') ? new User : User::findOrFail($request->user_id);
                
                $user->name = trim($request->name);
                $user->phone = trim($request->phone);
                
                if($request->filled('password')) {
                    $user->password = bcrypt($request->password);
                }

                if($user->save()) { 
                    $message = toastMessage ( " Your prfile has been successfully updated", 'success' );

                }else{
                    $message = toastMessage ( " Your prfile has not been successfully updated", 'error' );
                }

                // redirect
                session()->flash('toast', $message);
                
                return redirect()->back();
            }
        }

        $user = $request->user();

        $is_profile_page = true;

        return view('users.profile', compact('user', 'is_profile_page'));
    }
}
