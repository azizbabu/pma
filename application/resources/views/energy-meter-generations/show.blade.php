@extends('layouts.master')

@section('title') Energy Meter Generat
ion Details @endsection 
@section('page_title') Energy Meter Generations @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h3 class="card-title">Energy Meter Generation Details</h3>
	</div>
	<div class="card-body">
		<div class="row">

			<div class="col-md-12">
		        <div class="details-info">
		        	<strong>Plant: </strong>{{ $energyMeterGeneration->plant->name }} <br>
		        	<strong>Meter:</strong> {{ $energyMeterGeneration->meter->name }} <br>
		        	<strong>Gen Code:</strong> {{ strtoupper($energyMeterGeneration->gen_code) }} <br>
		        	<strong>Gen Date:</strong> {{ Carbon::parse($energyMeterGeneration->gen_date)->format('d M, Y') }} <br>
		        	<strong>Export Start:</strong> {{ $energyMeterGeneration->export_start }} <br>
		        	<strong>Export End:</strong> {{ $energyMeterGeneration->export_end }} <br>
		        	<strong>Import Start:</strong> {{ $energyMeterGeneration->import_start }} <br>
		        	<strong>Import End:</strong> {{ $energyMeterGeneration->import_end }} <br>
		        	
		        	<strong>Created at:</strong> {{ $energyMeterGeneration->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('energy-meter-generations/' . $energyMeterGeneration->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('energy-meter-generations/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


