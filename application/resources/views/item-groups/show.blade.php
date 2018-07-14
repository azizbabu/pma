@extends('layouts.master')

@section('title') Item Type Details @endsection 
@section('page_title') Item Types @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Item Type Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<h4><strong>Name: </strong>{{ $itemGroup->name }}</h4>
		        	<strong>Code:</strong> {{ strtoupper($itemGroup->code) }} <br>
		        	<strong>Created at:</strong> {{ $itemGroup->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('item-groups/' . $itemGroup->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('item-groups/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>
@endsection


