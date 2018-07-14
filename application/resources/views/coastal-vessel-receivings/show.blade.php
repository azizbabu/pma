@extends('layouts.master')

@section('title') Coastal Vessel Receiving Details @endsection 
@section('page_title') Mother Vessel Carrings @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Coastal Vessel Receiving Details</h4>
	</div>
	<div class="card-body">
		<div class="details-info">
        	<div class="row">
        		<div class="col-md-6">
        			@php 
					$coastalVesselCarring = $coastalVesselReceiving->coastalVesselCarring
		        	@endphp
		        	<strong>Coastal Vessel: </strong>{{ $coastalVesselCarring->coastalVessel->name }} <br>
		        	<strong>Code:</strong> {{ strtoupper($coastalVesselCarring->code) }} <br>
		        	<strong>Carring Date:</strong> {!! Carbon::parse($coastalVesselCarring->carring_date)->format('d M, Y') !!} <br>
		        	<strong>Invoice Quantity(MT):</strong> {!! $coastalVesselCarring->invoice_quantity !!} <br>
		        	<strong>Received Quantity(MT):</strong> {!! $coastalVesselCarring->received_quantity !!} <br>
		        	<strong>Transport Loss(%):</strong> {!! $coastalVesselReceiving->transport_loss !!}
        		</div>
        		<div class="col-md-6">
        			<strong>CVR Number: </strong>{{ strtoupper($coastalVesselReceiving->cvr_number) }}
        			<br>
        			<strong>CVR Date: </strong>{{ Carbon::parse($coastalVesselReceiving->cvr_date)->format('d M, Y') }}
        			<br>
        			<strong>CVR Qty: </strong>{{ $coastalVesselReceiving->cvr_qty }}
        			<br>
        			<strong>Load Qty: </strong>{{ $coastalVesselReceiving->load_qty }}
        			<br>
        			<strong>Loss: </strong>{{ $coastalVesselReceiving->loss }}
        			<br>
        			<strong>Lighter Vessel Name: </strong>{{ $coastalVesselReceiving->lighter_vessel_name ? $coastalVesselReceiving->lighter_vessel_name : 'N/A' }}
        			<br>
        			<strong>Plant: </strong>{{ $coastalVesselReceiving->plant ? $coastalVesselReceiving->plant->name : 'N/A' }}
        			<br>
        			<strong>Plant Receive Date: </strong>{{ $coastalVesselReceiving->plant_receive_date ? Carbon::parse($coastalVesselReceiving->plant_receive_date)->format('d M, Y') : '' }}
        			<br>
        		</div>
        	</div>
        </div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('coastal-vessel-receivings/' . $coastalVesselReceiving->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('coastal-vessel-receivings/list/'. $coastalVesselCarring->id) }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


