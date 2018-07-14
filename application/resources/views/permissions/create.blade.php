@extends('layouts.master')

@section('title') Create Permission @endsection 
@section('page_title') Permissions @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'permissions', 'role' => 'form', 'id'=>'permission-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Create Permission</h3>
			</div>
			<div class="card-body">
				@include('permissions.form')
			</div>
			<div class="card-footer">
				<button type="button" class="btn btn-info" onclick="addPermissions();"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


