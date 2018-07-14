@extends('layouts.master')

@section('title') List of Permissions @endsection 
@section('page_title') Permissions @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h3 class="card-title">
						List of Permissions
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('permissions/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
					</h3>
				</div>

				<div class="card-body">
					
					{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
		                <div class="row">
		                    <div class="col-md-3">
		                        <div class="form-group">
		                            {!! Form::select('role_id', $roles, request()->role_id, ['class' => 'form-control chosen-select']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-3">
		                        <div class="form-group">
		                            {!! Form::select('module_id', $modules, request()->module_id, ['class' => 'form-control chosen-select']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-3">
		                        <div class="form-group">
		                            {!! Form::select('page_id', $pages, request()->page_id, ['class' => 'form-control chosen-select']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-3">
		                        <div class="form-group">
		                        	<button class="btn btn-info" data-toggle="tooltip" title="Search"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
		                            <a href="{{ url()->current() }}" class="btn btn-default float-right" data-toggle="tooltip" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a>
		                        </div>
		                    </div>
		                </div>
                	{!! Form::close() !!}

					<div class="table-responsive">
						<table class="table table-striped table-bordered">
						    <thead>
						        <tr>
						        	<th width="7%">Role</th>
						        	<th width="16%">Module</th>
						            <th>Page</th>
						            <th width="8%" class="text-center">CanCreate</th>
						            <th width="8%" class="text-center">CanUpdate</th>
						            <th width="8%" class="text-center">CanDelete</th>
						            <th width="8%" class="text-center">CanView</th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($permissions as $permission)
						        <tr>
						            <td>{{ $permission->role->name }}</td>
						            <td>{{ $permission->module->name }}</td>
						            <td>{{ $permission->page->name }}</td>
						            <td class="text-center"><span class="badge badge-{{ $permission->can_create ? 'success' : 'danger' }}">{{ $permission->can_create ? 'Yes' : 'No' }}</span></td>
						            <td class="text-center"><span class="badge badge-{{ $permission->can_update ? 'success' : 'danger' }}">{{ $permission->can_update ? 'Yes' : 'No' }}</span></td>
						            <td class="text-center"><span class="badge badge-{{ $permission->can_delete ? 'success' : 'danger' }}">{{ $permission->can_delete ? 'Yes' : 'No' }}</span></td>
						            <td class="text-center"><span class="badge badge-{{ $permission->can_view ? 'success' : 'danger' }}">{{ $permission->can_view ? 'Yes' : 'No' }}</span></td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('permissions/' . $permission->id) }}" title="View permission"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('permissions/' . $permission->id . '/edit') }}" title="Edit Task"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$permission->id}}" data-action="{{ url('permissions/delete') }}" data-message="Are you sure, You want to delete this permission?" class="btn btn-danger btn-xs alert-dialog" title="Delete permission"><i class="fa fa-trash white"></i></a>
						            </td>
						        </tr>
						    @empty
						    	<tr>
						        	<td colspan="8" align="center">No Record Found!</td>
						        </tr>
						    @endforelse
						    </tbody>
						</table>
					</div>
				</div><!-- end card-body -->

				@if($permissions->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $permissions->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $permissions->links() !!}
							</div>
						</div>
					</div>
				</div>
				@endif
			</div><!-- end card  -->
		</div>
	</div>
</div>
@endsection