@extends('layouts.master')

@section('title') Edit Coastal Vessel @endsection 
@section('page_title') Coastal Vessels @endsection

@section('content')

<div class="container">
	{!! Form::model($coastalVessel, array('url' => 'coastal-vessels', 'role' => 'form', 'id'=>'coastal-vessel-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Coastal Vessel</h4>
			</div>
			<div class="card-body">
				@include('coastal-vessels.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('coastal_vessel_id', $coastalVessel->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



