@extends('layouts.master')

@section('title') Create Fuel Trade @endsection 
@section('page_title') Fuel Trades @endsection 

@section('content')
{!! Form::open(array('url' => 'fuel-trades', 'role' => 'form', 'id'=>'fuel-trade-create-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h4 class="card-title">Create Fuel Trade</h4>
		</div>
		<div class="card-body">
			@include('fuel-trades.form')
		</div>
		<div class="card-footer">
			<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
		</div>
	</div>		
{!! Form::close() !!}
@endsection


