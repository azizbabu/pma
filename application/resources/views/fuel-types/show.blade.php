@extends('layouts.master')

@section('title') Fuel Type Details @endsection 
@section('page_title') Fuel Types @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Fuel Type Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<h4><strong>Name: </strong>{{ $fuelType->name }}</h4>
		        	<strong>Code:</strong> {{ strtoupper($fuelType->code) }} <br>
		        	<strong>Created at:</strong> {{ $fuelType->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('fuel-types/' . $fuelType->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('fuel-types/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


