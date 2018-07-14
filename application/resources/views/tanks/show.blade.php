@extends('layouts.master')

@section('title') Tank Details @endsection 
@section('page_title') Tanks @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Tank Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<h4><strong>Terminal: </strong>{{ $tank->terminal->name }}</h4>
		        	<strong>Number:</strong> {{ strtoupper($tank->number) }} <br>
		        	<strong>Created at:</strong> {{ $tank->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('tanks/' . $tank->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('tanks/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


