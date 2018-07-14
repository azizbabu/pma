@extends('layouts.master')

@section('title') Fuel Trade Details @endsection 
@section('page_title') Fuel Trades @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Fuel Trade Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<strong>Tarnsaction Date: </strong>{{ Carbon::parse($fuelTrade->transaction_date)->format('d M, Y') }} <br>
		        	<strong>Party: </strong>{{ $fuelTrade->party->name }} <br>
		        	<strong>Terminal: </strong>{{ $fuelTrade->terminal->name }} <br>
		        	<strong>Loan Given Qty:</strong> {{ $fuelTrade->loan_given_qty }} <br>
		        	<strong>Loan Receive Qty:</strong> {{ $fuelTrade->loan_receive_qty }} <br>
		        	<strong>Loan Return Qty:</strong> {{ $fuelTrade->loan_return_qty }} <br>
		        	<strong>Created at:</strong> {{ $fuelTrade->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('fuel-trades/' . $fuelTrade->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('fuel-trades/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


