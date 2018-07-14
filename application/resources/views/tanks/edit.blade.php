@extends('layouts.master')

@section('title') Edit Tank @endsection 
@section('page_title') Tanks @endsection

@section('content')

<div class="container">
	{!! Form::model($tank, array('url' => 'tanks', 'role' => 'form', 'id'=>'tank-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Tank</h4>
			</div>
			<div class="card-body">
				@include('tanks.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('tank_id', $tank->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



