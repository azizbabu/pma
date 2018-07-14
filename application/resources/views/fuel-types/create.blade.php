@extends('layouts.master')

@section('title') Create Fuel Type @endsection 
@section('page_title') Fuel Types @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'fuel-types', 'role' => 'form', 'id'=>'fuel-type-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Create Fuel Type</h4>
			</div>
			<div class="card-body">
				@include('fuel-types.form')
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


