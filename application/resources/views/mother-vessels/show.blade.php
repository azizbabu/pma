@extends('layouts.master')

@section('title') Mother Vessel Details @endsection 
@section('page_title') Mother Vessels @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Mother Vessel Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<h4><strong>Name: </strong>{{ $motherVessel->name }}</h4>
		        	<strong>Code:</strong> {{ strtoupper($motherVessel->code) }} <br>
		        	<strong>Address:</strong> {!! $motherVessel->address ? $motherVessel->address : 'N/A' !!} <br>
		        	<strong>Contact Person Name:</strong> {!! $motherVessel->contact_person_name ? $motherVessel->contact_person_name : 'N/A' !!} <br>
		        	<strong>Contact Person Phone:</strong> {!! $motherVessel->contact_person_phone ? $motherVessel->contact_person_phone : 'N/A' !!} <br>
		        	<strong>Contact Person Email:</strong> {!! $motherVessel->contact_person_email ? $motherVessel->contact_person_email : 'N/A' !!} <br>
		        	<strong>Created at:</strong> {{ $motherVessel->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('mother-vessels/' . $motherVessel->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('mother-vessels/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


