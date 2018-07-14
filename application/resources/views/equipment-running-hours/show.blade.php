@extends('layouts.master')

@section('title') Equipment Running Hour Details @endsection 
@section('page_title') Equipment Running Hours @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h3 class="card-title">Equipment Running Hour Details</h3>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<strong>Plant:</strong> {{ $equipmentRunningHour->plant->name }} <br>
		        	<strong>Plant Equipment:</strong> {{ $equipmentRunningHour->plantEquipment->name }} <br>
		        	<strong>Running Year:</strong> {{ $equipmentRunningHour->running_year }} <br>
		        	<strong>Running Month:</strong> {{ getMonths($equipmentRunningHour->running_month) }} <br>
		        	<strong>Start Value: </strong>{{ $equipmentRunningHour->start_value }}
		        	<br>
		        	<strong>End Value: </strong>{{ $equipmentRunningHour->end_value }}
		        	<br>
		        	<strong>Diff Value: </strong>{{ $equipmentRunningHour->diff_value }}
		        	<br>
		        	<strong>Created at:</strong> {{ $equipmentRunningHour->created_at->format('d M, Y H:i a') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('equipment-running-hours/' . $equipmentRunningHour->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('equipment-running-hours/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


