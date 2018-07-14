@extends('layouts.master')

@section('title') Item Ledger Details @endsection 
@section('page_title') Item Ledgers @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Item Ledger Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<strong>Plant: </strong>{{ $itemLedger->plant->name }} <br>	
		        	<strong>Item: </strong>{{ $itemLedger->item->name }} <br>	
		        	<strong>Issue Code:</strong> {{ strtoupper($itemLedger->issue_code) }} <br>
		        	<strong>Issue Date:</strong> {{ Carbon::parse($itemLedger->issue_date)->format('d M, Y') }} <br>
		        	<strong>Remarks:</strong> {!! $itemLedger->remarks ? $itemLedger->remarks : 'N/A' !!} <br>
		        	<strong>Created at:</strong> {{ $itemLedger->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('item-ledgers/' . $itemLedger->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('item-ledgers/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


