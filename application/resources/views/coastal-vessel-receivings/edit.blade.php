@extends('layouts.master')

@section('title') Edit Coastal Vessel Receiving @endsection 
@section('page_title') Coastal Vessel Receivings @endsection

@section('content')

<div class="container">
	{!! Form::model($coastalVesselReceiving, array('url' => 'coastal-vessel-receivings', 'role' => 'form', 'id'=>'coastal-vessel-receiving-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Coastal Vessel Receiving</h4>
			</div>
			<div class="card-body">
				@include('coastal-vessel-receivings.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('coastal_vessel_carring_id', $coastalVesselCarring->id) !!}
				{!! Form::hidden('coastal_vessel_receiving_id', $coastalVesselReceiving->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



