@extends('layouts.master')

@section('title') Daily Plant Generation Details @endsection 
@section('page_title') Daily Plant Generations @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h3 class="card-title">Daily Plant Generation Details</h3>
	</div>
	<div class="card-body">
		<div class="details-info">
        	<div class="row">
        		<div class="col-md-6">
        			<strong>Plant: </strong>{{ $dailyPlantGeneration->plant->name }} <br>
        			<strong>Opration Date:</strong> {{ Carbon::parse($dailyPlantGeneration->operation_date)->format('d M, Y') }} <br>
        			<strong>Remarks:</strong> {{ $dailyPlantGeneration->remarks }} <br>
		        	<strong>Comment:</strong> {{ $dailyPlantGeneration->comment }}
        		</div>
        		<div class="col-md-6">
        			<strong>Plant Load Factor (%):</strong> {{ $dailyPlantGeneration->plant_load_factor }} <br>
		        	<strong>Fuel Consumption (MT):</strong> {{ $dailyPlantGeneration->plant_fuel_consumption }} <br>
		        	<strong>Total HFO Stock (MT):</strong> {{ $dailyPlantGeneration->total_hfo_stock }} <br>
		        	<strong>Reference LHV (KJ/Kg):</strong> {{ $dailyPlantGeneration->reference_lhv }} <br>
		        	<strong>Aux. Boiler HFO Consumption (MT):</strong> {{ $dailyPlantGeneration->aux_boiler_hfo_consumption }} 
        		</div>
        	</div>
        </div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('daily-plant-generations/' . $dailyPlantGeneration->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('daily-plant-generations/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


