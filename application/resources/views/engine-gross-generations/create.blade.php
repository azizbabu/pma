@extends('layouts.master')

@section('title') Create Engine Gross Generation @endsection 
@section('page_title') Engine Gross Generations @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'engine-gross-generations', 'role' => 'form', 'id'=>'engine-gross-generation-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Create Engine Gross Generation</h3>
			</div>
			<div class="card-body">
				@include('engine-gross-generations.form')
			</div>
			<div class="card-footer">
				<button type="button" class="btn btn-info" onclick="addEnergyGrossGenerations();"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


