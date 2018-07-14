@extends('layouts.master')

@section('title') Mother Vessel Carring Details @endsection 
@section('page_title') Mother Vessel Carrings @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Mother Vessel Carring Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<strong>Mother Vessel: </strong>{{ $motherVesselCarring->motherVessel->name }} <br>	
		        	<strong>Code:</strong> {{ strtoupper($motherVesselCarring->code) }} <br>
		        	<strong>LC Number:</strong> {{ strtoupper($motherVesselCarring->lc_number) }} <br>
		        	<strong>Carring Date:</strong> {!! Carbon::parse($motherVesselCarring->carring_date)->format('d M, Y') !!} <br>
		        	<strong>Loading Date:</strong> {!! Carbon::parse($motherVesselCarring->loading_date)->format('d M, Y') !!} <br>
		        	<strong>Received Date:</strong> {!! Carbon::parse($motherVesselCarring->received_date)->format('d M, Y') !!} <br>
		        	<strong>Invoice Quantity(MT):</strong> {!! $motherVesselCarring->invoice_quantity !!} <br>
		        	<strong>Received Quantity(MT):</strong> {!! $motherVesselCarring->received_quantity !!} <br>
		        	<strong>Transport Loss(%):</strong> {!! $motherVesselCarring->transport_loss !!} <br>
		        	<strong>Comment:</strong> {!! $motherVesselCarring->comment ? $motherVesselCarring->comment : 'N/A' !!} <br>
		        	<strong>Created at:</strong> {{ $motherVesselCarring->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('mother-vessel-carrings/' . $motherVesselCarring->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('mother-vessel-carrings/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


