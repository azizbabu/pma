@extends('layouts.master')

@section('title') Edit Mother Vessel Carring @endsection 
@section('page_title') Mother Vessel Carrings @endsection

@section('content')

<div class="container">
	{!! Form::model($motherVesselCarring, array('url' => 'mother-vessel-carrings', 'role' => 'form', 'id'=>'mother-vessel-carring-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Mother Vessel Carring</h4>
			</div>
			<div class="card-body">
				@include('mother-vessel-carrings.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('mother_vessel_carring_id', $motherVesselCarring->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



