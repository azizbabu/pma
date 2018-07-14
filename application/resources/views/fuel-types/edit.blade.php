@extends('layouts.master')

@section('title') Edit Fuel Type @endsection 
@section('page_title') Fuel Types @endsection

@section('content')

<div class="container">
	{!! Form::model($fuelType, array('url' => 'fuel-types', 'role' => 'form', 'id'=>'fuel-type-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Fuel Type</h4>
			</div>
			<div class="card-body">
				@include('fuel-types.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('fuel_type_id', $fuelType->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



