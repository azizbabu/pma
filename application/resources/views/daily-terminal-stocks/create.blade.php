@extends('layouts.master')

@section('title') Create Daily Terminal Stock @endsection 
@section('page_title') Daily Terminal Stocks @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'daily-terminal-stocks', 'role' => 'form', 'id'=>'daily-terminal-stock-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Create Daily Terminal Stock</h3>
			</div>
			<div class="card-body">
				@include('daily-terminal-stocks.form')
			</div>
			<div class="card-footer">
				<button type="button" class="btn btn-info" onclick="addDailyTerminalStocks();"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


