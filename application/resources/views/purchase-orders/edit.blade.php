@extends('layouts.master')

@section('title') Edit Purchase Order @endsection 
@section('page_title') Purchase Orders @endsection

@section('content')

{!! Form::model($purchaseOrder, array('url' => 'purchase-orders/'.$purchaseOrder->id, 'role' => 'form', 'id'=>'purchase-order-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h4 class="card-title">Edit Purchase Order</h4>
		</div>
		<div class="card-body">
			@include('purchase-orders.form')
		</div>
		<div class="card-footer">
			{!! Form::hidden('po_number', $purchaseOrder->po_number) !!}
			<button type="button" class="btn btn-info" onclick="addPurchaseOrder();"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
		</div>
	</div>		
{!! Form::close() !!}

@endsection



