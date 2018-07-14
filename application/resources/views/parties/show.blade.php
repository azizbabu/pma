@extends('layouts.master')

@section('title') Party Details @endsection 
@section('page_title') Parties @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Party Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<h4><strong>Name: </strong>{{ $party->name }}</h4>
		        	<strong>Code:</strong> {{ strtoupper($party->code) }} <br>
		        	<strong>Mobile:</strong> {{ $party->mobile ? $party->mobile : 'N/A' }} <br>
		        	<strong>Email:</strong> {{ $party->email ? $party->email : 'N/A' }} <br>
		        	<strong>Address:</strong> {!! $party->address ? $party->address : 'N/A' !!} <br>
		        	<strong>Contact Person Name:</strong> {!! $party->contact_person_name ? $party->contact_person_name : 'N/A' !!} <br>
		        	<strong>Contact Person Mobile:</strong> {!! $party->contact_person_mobile ? $party->contact_person_mobile : 'N/A' !!} <br>
		        	<strong>Contact Person Email:</strong> {!! $party->contact_person_email ? $party->contact_person_email : 'N/A' !!} <br>
		        	<strong>Created at:</strong> {{ $party->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('parties/' . $party->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('parties/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


