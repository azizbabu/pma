@extends('layouts.master')

@section('title') Plant Equipment Details @endsection 
@section('page_title') Plant Equipments @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Plant Equipment Details</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<h4><strong>Name: </strong>{{ $plantEquipment->name }}</h4>
		        	<strong>Plant: </strong>{{ $plantEquipment->plant->name }}<br>
		        	<strong>Code:</strong> {{ strtoupper($plantEquipment->code) }} <br>
		        	<strong>Created at:</strong> {{ $plantEquipment->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('plant-equipments/' . $plantEquipment->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('plant-equipments/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


