@extends('layouts.master')

@section('title') Create Coastal Vessel Receiving @endsection 
@section('page_title') Coastal Vessel Receivings @endsection 

@section('content')

{!! Form::open(array('url' => 'coastal-vessel-receivings', 'role' => 'form', 'id'=>'coastal-vessel-receiving-create-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h4 class="card-title">Create Coastal Vessel Receiving</h4>
		</div>
		<div class="card-body">
			@include('coastal-vessel-receivings.form')
		</div>
		<div class="card-footer">
			{!! Form::hidden('coastal_vessel_carring_id', $coastalVesselCarring->id) !!}
			<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
		</div>
	</div>		
{!! Form::close() !!}

@endsection


