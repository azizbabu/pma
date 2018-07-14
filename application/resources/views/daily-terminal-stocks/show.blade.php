@extends('layouts.master')

@section('title') Daily Terminal Stock Details @endsection 
@section('page_title') Daily Terminal Stocks @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h3 class="card-title">Daily Terminal Stock Details</h3>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<strong>Transaction Date: </strong>{{ Carbon::parse($dailyTerminalStock->date)->format('d M, Y') }} <br>
		        	<strong>Terminal Name:</strong> {{ $dailyTerminalStock->terminal->name }} <br>
		        	<strong>Tank Number:</strong> {{ $dailyTerminalStock->tank->number }} <br>
		        	<strong>Tank Stock:</strong> {{ $dailyTerminalStock->tank_stock }} <br>
		        	<strong>Comment:</strong> {!! $dailyTerminalStock->comment !!} <br>
		        	<strong>Created at:</strong> {{ $dailyTerminalStock->created_at->format('d M, Y H:i a') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('daily-terminal-stocks/' . $dailyTerminalStock->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('daily-terminal-stocks/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


