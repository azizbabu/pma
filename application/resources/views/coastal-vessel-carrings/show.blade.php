@extends('layouts.master')

@section('title') Coastal Vessel Carring Details @endsection 
@section('page_title') Coastal Vessel Carrings @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Coastal Vessel Carring Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<strong>Coastal Vessel: </strong>{{ $coastalVesselCarring->coastalVessel->name }} <br>	
		        	<strong>Plant: </strong>{{ $coastalVesselCarring->plant ? $coastalVesselCarring->plant->name : 'N/A' }} <br>	
		        	<strong>Code:</strong> {{ strtoupper($coastalVesselCarring->code) }} <br>
		        	<strong>Carring Date:</strong> {!! Carbon::parse($coastalVesselCarring->carring_date)->format('d M, Y') !!} <br>
		        	<strong>Loading Date:</strong> {!! Carbon::parse($coastalVesselCarring->loading_date)->format('d M, Y') !!} <br>
		        	<strong>Received Date:</strong> {!! Carbon::parse($coastalVesselCarring->received_date)->format('d M, Y') !!} <br>
		        	<strong>Invoice Quantity(MT):</strong> {!! $coastalVesselCarring->invoice_quantity !!} <br>
		        	<strong>Received Quantity(MT):</strong> {!! $coastalVesselCarring->received_quantity !!} <br>
		        	<strong>Transport Loss(%):</strong> {!! $coastalVesselCarring->transport_loss !!} <br>
		        	<strong>Lighter Vessel Name:</strong> {!! $coastalVesselCarring->lighter_vessel_name ? $coastalVesselCarring->lighter_vessel_name : 'N/A' !!} <br>
		        	<strong>Comment:</strong> {!! $coastalVesselCarring->comment ? $coastalVesselCarring->comment : 'N/A' !!} <br>
		        	<strong>Created at:</strong> {{ $coastalVesselCarring->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('coastal-vessel-carrings/' . $coastalVesselCarring->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('coastal-vessel-carrings/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


