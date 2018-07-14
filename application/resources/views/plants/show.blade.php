@extends('layouts.master')

@section('title') Plant Details @endsection 
@section('page_title') Plants @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Plant Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<h4><strong>Name: </strong>{{ $plant->name }}</h4>
		        	<strong>Code:</strong> {{ strtoupper($plant->code) }} <br>
		        	<strong>Address:</strong> {!! $plant->address ? $plant->address : 'N/A' !!} <br>
		        	<strong>Created at:</strong> {{ $plant->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('plants/' . $plant->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('plants/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


