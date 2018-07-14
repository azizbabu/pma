@extends('layouts.master')

@section('title') Edit Fuel Trade @endsection 
@section('page_title') Fuel Trades @endsection

@section('content')
{!! Form::model($fuelTrade, array('url' => 'fuel-trades', 'role' => 'form', 'id'=>'fuel-tarde-edit-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h4 class="card-title">Edit Fuel Trade</h4>
		</div>
		<div class="card-body">
			@include('fuel-trades.form')
		</div>
		<div class="card-footer">
			{!! Form::hidden('fuel_trade_id', $fuelTrade->id) !!}
			<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
		</div>
	</div>		
{!! Form::close() !!}
@endsection



