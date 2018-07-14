@extends('layouts.master')

@section('title') Edit Purchase Requisition @endsection 
@section('page_title') Purchase Requisitions @endsection

@section('content')
{!! Form::model($purchaseRequisition, array('url' => 'purchase-requisitions', 'role' => 'form', 'id'=>'purchase-requisition-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h4 class="card-title">Edit Purchase Requisition</h4>
		</div>
		<div class="card-body">
			@include('purchase-requisitions.form')
		</div>
		<div class="card-footer">
			{!! Form::hidden('requisition_code', $purchaseRequisition->requisition_code) !!}
			<button type="button" class="btn btn-info" onclick="addPurchaseRequisitions();"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
		</div>
	</div>		
{!! Form::close() !!}
@endsection



