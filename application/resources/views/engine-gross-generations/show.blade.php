@extends('layouts.master')

@section('title') Engine Gross Generation Details @endsection 
@section('page_title') Engine Gross Generations @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h3 class="card-title">Engine Gross Generation Details</h3>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<strong>Plant: </strong>{{ $engineGrossGeneration->plant->name }} <br>
		        	<strong>Engine:</strong> {{ $engineGrossGeneration->engine->name }} <br>
		        	<strong>OP Code:</strong> {{ strtoupper($engineGrossGeneration->op_code) }} <br>
		        	<strong>OP Date:</strong> {{ Carbon::parse($engineGrossGeneration->op_date)->format('d M, Y') }} <br>
		        	<strong>Start Time:</strong> {{ $engineGrossGeneration->start_time }} <br>
		        	<strong>End Time:</strong> {{ $engineGrossGeneration->end_time }} <br>
		        	<strong>Diff Time:</strong> {{ $engineGrossGeneration->diff_time }} <br>
		        	<strong>Start Op MWH:</strong> {{ number_format($engineGrossGeneration->start_op_mwh, 2) }} <br>
		        	<strong>End Op MWH:</strong> {{ number_format($engineGrossGeneration->end_op_mwh, 2) }} <br>
		        	
		        	<strong>Created at:</strong> {{ $engineGrossGeneration->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('engine-gross-generations/' . $engineGrossGeneration->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('engine-gross-generations/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


