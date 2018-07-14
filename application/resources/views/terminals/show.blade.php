@extends('layouts.master')

@section('title') Terminal Details @endsection 
@section('page_title') Terminals @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Terminal Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<h4><strong>Name: </strong>{{ $terminal->name }}</h4>
		        	<strong>Code:</strong> {{ strtoupper($terminal->code) }} <br>
		        	<strong>Address:</strong> {!! $terminal->address ? $terminal->address : 'N/A' !!} <br>
		        	<strong>Manager Name:</strong> {!! $terminal->manager_name ? $terminal->manager_name : 'N/A' !!} <br>
		        	<strong>Manager Phone:</strong> {!! $terminal->manager_phone ? $terminal->manager_phone : 'N/A' !!} <br>
		        	<strong>Manager Email:</strong> {!! $terminal->manager_email ? $terminal->manager_email : 'N/A' !!} <br>
		        	<strong>Created at:</strong> {{ $terminal->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('terminals/' . $terminal->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('terminals/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


