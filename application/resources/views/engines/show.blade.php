@extends('layouts.master')

@section('title') Engine Details @endsection 
@section('page_title') Engines @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Engine Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<h4><strong>Name: </strong>{{ $engine->name }}</h4>
		        	<strong>Plant:</strong> {{ $engine->plant->name }} <br>
		        	<strong>Number:</strong> {{ strtoupper($engine->number) }} <br>
		        	<strong>Capacity:</strong> {{ $engine->capacity }} <br>
		        	<strong>Unit:</strong> {{ $engine->unit }} <br>
		        	<strong>Created at:</strong> {{ $engine->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('engines/' . $engine->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('engines/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


