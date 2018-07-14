@extends('layouts.master')

@section('title') Create Item Ledger @endsection 
@section('page_title') Item Ledgers @endsection 

@section('content')
{!! Form::open(array('url' => 'item-ledgers', 'role' => 'form', 'id'=>'item-ledger-create-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h4 class="card-title">Create Item Ledger</h4>
		</div>
		<div class="card-body">
			@include('item-ledgers.form')
		</div>
		<div class="card-footer">
			<button type="button" class="btn btn-info" onclick="addItemLedger();"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
		</div>
	</div>		
{!! Form::close() !!}

@endsection


