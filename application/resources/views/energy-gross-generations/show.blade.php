@extends('layouts.master')

@section('title') Energy Gross Generation Details @endsection 
@section('page_title') Energy Gross Generations @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h3 class="card-title">Energy Gross Generation Details</h3>
	</div>
	<div class="card-body">
		<div class="details-info">
        	<div class="row">
        		<div class="col-md-6">
        			<strong>Plant: </strong>{{ $energyGrossGeneration->plant->name }} <br>
        			<strong>Meter:</strong> {{ $energyGrossGeneration->meter->name }} <br>
        			<strong>Export Start (KWH):</strong> {{ $energyGrossGeneration->export_start_kwh }} <br>
		        	<strong>Export End (KWH):</strong> {{ $energyGrossGeneration->export_end_kwh }} <br>
		        	<strong>Import Start (KWH):</strong> {{ $energyGrossGeneration->import_start_kwh }} <br>
		        	<strong>Import End (KWH):</strong> {{ $energyGrossGeneration->import_end_kwh }} <br>
        		</div>
        		<div class="col-md-6">
        			<strong>OP Code:</strong> {{ strtoupper($energyGrossGeneration->op_code) }} <br>
		        	<strong>OP Date:</strong> {{ Carbon::parse($energyGrossGeneration->op_date)->format('d M, Y') }} <br>
		        	<strong>Export Start (KVARH):</strong> {{ $energyGrossGeneration->export_start_kvarh }} <br>
		        	<strong>Export End (KVARH):</strong> {{ $energyGrossGeneration->export_end_kvarh }} <br>
		        	<strong>Import Start (KVARH):</strong> {{ $energyGrossGeneration->import_start_kvarh }} <br>
		        	<strong>Import End (KVARH):</strong> {{ $energyGrossGeneration->import_end_kvarh }} <br>
		        	<strong>Created at:</strong> {{ $energyGrossGeneration->created_at->format('d M, Y H:i A') }}
        		</div>
        	</div>
        </div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('energy-gross-generations/' . $energyGrossGeneration->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('energy-gross-generations/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


