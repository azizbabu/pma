@extends('layouts.master')

@section('title') Edit Stock Receive Register @endsection 
@section('page_title') Stock Receive Registers @endsection

@section('content')

<div class="container">
	{!! Form::model($stockReceiveRegister, array('url' => 'stock-receive-registers/'.$stockReceiveRegister->id, 'role' => 'form', 'id'=>'stock-receive-register-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Stock Receive Register</h4>
			</div>
			<div class="card-body">
				@include('stock-receive-registers.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('receive_code', $stockReceiveRegister->receive_code) !!}
				<button type="button" class="btn btn-info" onclick="addStockReceiveRegisters();"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



