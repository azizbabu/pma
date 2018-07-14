<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;
use App\Module;
use App\Page;
use App\Role;
use Validator;

class PermissionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('super_admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = Permission::query();

        $data = [];

        if($request->filled('role_id')) {
            $query->where('role_id', trim($request->role_id));

            $data['role_id'] = trim($request->role_id);
        }

        if($request->filled('module_id')) {
            $query->where('module_id', trim($request->module_id));

            $data['module_id'] = trim($request->module_id);
        }

        if($request->filled('page_id')) {
            $query->where('page_id', trim($request->page_id));

            $data['page_id'] = trim($request->page_id);
        }

        $permissions = $query->latest()->paginate();

        $permissions->paginationSummary = getPaginationSummary($permissions->total(), $permissions->perPage(), $permissions->currentPage());

        if($data) {
            $permissions->appends($data);
        }

        $roles = Role::getDropDownList(true, 1);
        $modules = Module::getDropDownList();
        $pages = Page::getDropDownList();


        return view('permissions.index', compact('permissions', 'roles', 'modules', 'pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::getDropDownList(true, 1);
        $modules = Module::getDropDownList();

        return view('permissions.create', compact('roles', 'modules'));
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
            'role_id'  => 'required|integer|min:1',
            'module_id' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            
            return response()->json([
                'status'    => 400,
                'error'     => $validator->errors(),
            ]);
        }else{

            if(!$request->has('page_id')) {

                return response()->json([
                    'status'    => 404,
                    'type'      => 'error',
                    'message'   => 'The page field is required',
                ]);
            }

            // Delete already added permissions
            Permission::whereRoleId(trim($request->role_id))->whereModuleId(trim($request->module_id))->forceDelete();

            $total_pages = count($request->page_id);
            $addedPermissionNumber = 0;
            
            foreach($request->page_id as $key => $value) { 
                // store
                $permission = new Permission;
                $permission->role_id = trim($request->role_id);
                $permission->module_id = trim($request->module_id);
                $permission->page_id = $request->page_id[$key];
                $permission->can_create = !empty($request->can_create[$key]) ? true : false;
                $permission->can_update = !empty($request->can_update[$key]) ? true : false;
                $permission->can_delete = !empty($request->can_delete[$key]) ? true : false;
                $permission->can_view = !empty($request->can_view[$key]) ? true : false;

                if($permission->save()) {
                    $addedPermissionNumber++;
                }
            }

            if($addedPermissionNumber) {
                
                return response()->json([
                    'status'    => 200,
                    'type'    => 'success',
                    'message'    => $addedPermissionNumber . ' no permission(s) have been successfully added.',
                ]);
            }else {
                
                return response()->json([
                    'status'    => 404,
                    'type'    => 'error',
                    'message'    => 'Permission has not been added.',
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return view('permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $permission->can_create = !empty($request->can_create) ? true : false;
        $permission->can_update = !empty($request->can_update) ? true : false;
        $permission->can_delete = !empty($request->can_delete) ? true : false;
        $permission->can_view = !empty($request->can_view) ? true : false;

        if($permission->save()) {
            $message = toastMessage('Permission has been updated');
        }else {
            $message = toastMessage('Permission has not been updated', 'error');
        }

        session()->flash('toast', $message);

        return redirect('permissions/list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(Permission::destroy($request->hdnResource)) {
            $message = toastMessage('Permission has been successfully removed.','success');
        }else{
            $message = toastMessage('Permission has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);
        
        return back();
    }

    /**
     * Get pages based on module id
     *
     * @param \Illuminate\Http\Request $request.
     * @param int $module_id
     * @return \Illuminate\Http\Response
     */
    public function getPages(Request $request, $module_id)
    {
        $role = Role::find(trim($request->role_id));

        if(!$role) {
            return response()->json([
                'type'      => 'error',
                'message'   => 'Role not found!',
            ]);
        }

        $module = Module::find($module_id);

        if(!$module) {
            return response()->json([
                'type'      => 'error',
                'message'   => 'Module not found!',
            ]);
        }

        $pages = $module->pages()->where('name', '<>', 'User Permission')->get(['id', 'name']);

        foreach($pages as $page) {
            
            $permission = $page->permissions()->whereRoleId(trim($request->role_id))->first([
                'can_create', 
                'can_update', 
                'can_delete', 
                'can_update', 
                'can_view'
            ]);

            if($permission) {
                $can_create = $permission->can_create;
                $can_update = $permission->can_update;
                $can_delete = $permission->can_delete;
                $can_view = $permission->can_view;
            }else {
                $can_create = false;
                $can_update = false;
                $can_delete = false;
                $can_view   = false;
            }

            $page->can_create = $can_create;
            $page->can_update = $can_update;
            $page->can_delete = $can_delete;
            $page->can_view = $can_view;
        }

        return $pages->toJson();
    }
}
