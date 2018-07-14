@extends('layouts.master')

@section('title') Meter Details @endsection 
@section('page_title') Meters @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Meter Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<h4><strong>Name: </strong>{{ $meter->name }}</h4>
		        	<strong>Plant:</strong> {{ $meter->plant->name }} <br>
		        	<strong>Number:</strong> {{ strtoupper($meter->number) }} <br>
		        	<strong>Capacity:</strong> {{ $meter->capacity }} <br>
		        	<strong>Unit:</strong> {{ $meter->unit }} <br>
		        	<strong>Created at:</strong> {{ $meter->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('meters/' . $meter->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('meters/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


