@extends('layouts.master')

@section('title') Engine Generation Details @endsection 
@section('page_title') Engine Generations @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h3 class="card-title">Engine Generation Details</h3>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<strong>Plant: </strong>{{ $engineGeneration->plant->name }} <br>
		        	<strong>Engine:</strong> {{ $engineGeneration->engine->name }} <br>
		        	<strong>Gen Code:</strong> {{ strtoupper($engineGeneration->gen_code) }} <br>
		        	<strong>Gen Date:</strong> {{ Carbon::parse($engineGeneration->gen_date)->format('d M, Y') }} <br>
		        	<strong>Start:</strong> {{ $engineGeneration->start }} <br>
		        	<strong>End:</strong> {{ $engineGeneration->end }} <br>
		        	<strong>Diff:</strong> {{ $engineGeneration->total }} <br>
		        	<strong>Created at:</strong> {{ $engineGeneration->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('engine-generations/' . $engineGeneration->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('engine-generations/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


