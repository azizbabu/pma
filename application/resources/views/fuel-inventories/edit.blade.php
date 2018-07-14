@extends('layouts.master')

@section('title') Edit Fuel Inventory @endsection 
@section('page_title') Fuel Inventories @endsection

@section('content')

{!! Form::model($fuelInventory, array('url' => 'fuel-inventories', 'role' => 'form', 'id'=>'coastal-vessel-carring-edit-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h4 class="card-title">Edit Fuel Inventory</h4>
		</div>
		<div class="card-body">
			@include('fuel-inventories.form')
		</div>
		<div class="card-footer">
			{!! Form::hidden('fuel_inventory_id', $fuelInventory->id) !!}
			<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
		</div>
	</div>		
{!! Form::close() !!}

@endsection



