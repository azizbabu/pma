@extends('layouts.master')

@section('title') Item Details @endsection 
@section('page_title') Items @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Item Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<strong>Item Group: </strong>{{ $item->itemGroup->name }} <br>	
		        	<strong>Item Name: </strong>{{ $item->name }} <br>	
		        	<strong>Code:</strong> {{ strtoupper($item->code) }} <br>
		        	<strong>PR Number:</strong> {{ strtoupper($item->pr_number) }} <br>
		        	<strong>Source Type:</strong> {{ ucfirst($item->source_type) }} <br>
		        	<strong>Stock Type:</strong> {{ ucfirst($item->stock_type) }} <br>
		        	<strong>Opening Qty:</strong> {{ $item->opening_qty }} <br>
		        	<strong>Average Price:</strong> {{ $item->avg_price }} <br>
		        	<strong>Safety Stock Qty:</strong> {{ $item->safety_stock_qty }} <br>
		        	<strong>Remarks:</strong> {!! $item->remarks !!} <br>
		        	<strong>Created at:</strong> {{ $item->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('items/' . $item->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('items/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


