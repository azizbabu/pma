@extends('layouts.master')

@section('title') Create User @endsection 
@section('page_title') Users @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'users', 'role' => 'form', 'id'=>'user-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Create User</h3>
			</div>
			<div class="card-body">
				@include('users.form')
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


