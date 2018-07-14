@extends('layouts.master')

@section('title') Permission Details @endsection 
@section('page_title') Permissions @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h3 class="card-title">Permission Details</h3>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<strong>Role: </strong>{{ $permission->role->name }} <br>
		        	<strong>Module:</strong> {{ $permission->module->name }} <br>
		        	<strong>Page:</strong> {{ $permission->page->name }} <br>
		        	<strong>Can Create:</strong> <span class="badge badge-{{ $permission->can_create ? 'success' : 'danger' }}">{{ $permission->can_create ? 'Yes' : 'No' }}</span> <br>
		        	<strong>Can Update:</strong> <span class="badge badge-{{ $permission->can_update ? 'success' : 'danger' }}">{{ $permission->can_update ? 'Yes' : 'No' }}</span> <br>
		        	<strong>Can Delete:</strong> <span class="badge badge-{{ $permission->can_delete ? 'success' : 'danger' }}">{{ $permission->can_delete ? 'Yes' : 'No' }}</span> <br>
		        	<strong>Can View:</strong> <span class="badge badge-{{ $permission->can_view ? 'success' : 'danger' }}">{{ $permission->can_view ? 'Yes' : 'No' }}</span> <br>
		        	<strong>Created at:</strong> {{ $permission->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('permissions/' . $permission->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('permissions/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


