@extends('layouts.master')

@section('title') Edit Coastal Vessel Carring @endsection 
@section('page_title') Coastal Vessel Carrings @endsection

@section('content')

<div class="container">
	{!! Form::model($coastalVesselCarring, array('url' => 'coastal-vessel-carrings', 'role' => 'form', 'id'=>'coastal-vessel-carring-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Coastal Vessel Carring</h4>
			</div>
			<div class="card-body">
				@include('coastal-vessel-carrings.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('coastal_vessel_carring_id', $coastalVesselCarring->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



