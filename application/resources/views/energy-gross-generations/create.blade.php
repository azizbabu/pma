@extends('layouts.master')

@section('title') Create Energy Gross Generation @endsection 
@section('page_title') Energy Gross Generations @endsection 

@section('content')
{!! Form::open(array('url' => 'energy-gross-generations', 'role' => 'form', 'id'=>'energy-gross-generation-create-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h3 class="card-title">Create Energy Gross Generation</h3>
		</div>
		<div class="card-body">
			@include('energy-gross-generations.form')
		</div>
		<div class="card-footer">
			<button type="button" class="btn btn-info" onclick="addEnergyGrossGenerations();"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
		</div>
	</div>		
{!! Form::close() !!}

@endsection


