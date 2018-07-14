@extends('layouts.master')

@section('title') Create Energy Meter Generation @endsection 
@section('page_title') Energy Meter Generations @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'energy-meter-generations', 'role' => 'form', 'id'=>'energy-meter-generation-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Create Energy Meter Generation</h3>
			</div>
			<div class="card-body">
				@include('energy-meter-generations.form')
			</div>
			<div class="card-footer">
				<button type="button" class="btn btn-info" onclick="addEnergyMeterGenerations();"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


