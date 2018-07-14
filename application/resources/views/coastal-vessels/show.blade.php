@extends('layouts.master')

@section('title') Coastal Vessel Details @endsection 
@section('page_title') Coastal Vessels @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Coastal Vessel Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<h4><strong>Name: </strong>{{ $coastalVessel->name }}</h4>
		        	<strong>Code:</strong> {{ strtoupper($coastalVessel->code) }} <br>
		        	<strong>Address:</strong> {!! $coastalVessel->address ? $coastalVessel->address : 'N/A' !!} <br>
		        	<strong>Contact Person Name:</strong> {!! $coastalVessel->contact_person_name ? $coastalVessel->contact_person_name : 'N/A' !!} <br>
		        	<strong>Contact Person Phone:</strong> {!! $coastalVessel->contact_person_phone ? $coastalVessel->contact_person_phone : 'N/A' !!} <br>
		        	<strong>Contact Person Email:</strong> {!! $coastalVessel->contact_person_email ? $coastalVessel->contact_person_email : 'N/A' !!} <br>
		        	<strong>Created at:</strong> {{ $coastalVessel->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('coastal-vessels/' . $coastalVessel->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('coastal-vessels/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


