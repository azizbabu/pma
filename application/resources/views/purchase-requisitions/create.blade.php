@extends('layouts.master')

@section('title') Create Purchase Requisition @endsection 
@section('page_title') Purchase Requisitions @endsection 

@section('content')
{!! Form::open(array('url' => 'purchase-requisitions', 'role' => 'form', 'id'=>'purchase-requisition-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h4 class="card-title">Create Purchase Requisition</h4>
		</div>
		<div class="card-body">
			@include('purchase-requisitions.form')
		</div>
		<div class="card-footer">
			<button type="button" class="btn btn-info" onclick="addPurchaseRequisitions();"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
		</div>
	</div>		
{!! Form::close() !!}
@endsection


