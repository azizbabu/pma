@extends('layouts.master')

@section('title') Create Purchase Order @endsection 
@section('page_title') Purchase Orders @endsection 

@section('content')
{!! Form::open(array('url' => 'purchase-orders', 'role' => 'form', 'id'=>'purchase-order-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h4 class="card-title">Create Purchase Order</h4>
		</div>
		<div class="card-body">
			@include('purchase-orders.form')
		</div>
		<div class="card-footer">
			<button type="button" class="btn btn-info" onclick="addPurchaseOrder();"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
		</div>
	</div>		
{!! Form::close() !!}

@endsection


