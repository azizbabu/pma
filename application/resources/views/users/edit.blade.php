@extends('layouts.master')

@section('title') Edit User @endsection 
@section('page_title') Users @endsection

@section('content')

<div class="container">
	{!! Form::model($user, array('url' => 'users', 'role' => 'form', 'id'=>'user-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Edit User</h3>
			</div>
			<div class="card-body">
				@include('users.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('user_id', $user->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



