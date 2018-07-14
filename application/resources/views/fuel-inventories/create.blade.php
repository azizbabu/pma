@extends('layouts.master')

@section('title') Create Fuel Inventory @endsection 
@section('page_title') Fuel Inventories @endsection 

@section('content')

{!! Form::open(array('url' => 'fuel-inventories', 'role' => 'form', 'id'=>'fuel-inventory-create-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h4 class="card-title">Create Fuel Inventory</h4>
		</div>
		<div class="card-body">
			@include('fuel-inventories.form')
		</div>
		<div class="card-footer">
			<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
		</div>
	</div>		
{!! Form::close() !!}

@endsection


