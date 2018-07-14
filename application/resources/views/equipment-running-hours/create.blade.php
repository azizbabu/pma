@extends('layouts.master')

@section('title') Create Equipment Running Hour @endsection 
@section('page_title') Equipment Running Hours @endsection 

@section('content')
	{!! Form::open(array('url' => 'equipment-running-hours', 'role' => 'form', 'id'=>'equipment-running-hour-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Create Equipment Running Hour</h3>
			</div>
			<div class="card-body">
				@include('equipment-running-hours.form')
			</div>
			<div class="card-footer">
				<button type="button" class="btn btn-info" onclick="addEquipmentRunningHours();"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
@endsection


