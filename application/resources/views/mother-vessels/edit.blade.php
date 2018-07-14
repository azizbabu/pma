@extends('layouts.master')

@section('title') Edit Mother Vessel @endsection 
@section('page_title') Mother Vessels @endsection

@section('content')

<div class="container">
	{!! Form::model($motherVessel, array('url' => 'mother-vessels', 'role' => 'form', 'id'=>'mother-vessel-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Mother Vessel</h4>
			</div>
			<div class="card-body">
				@include('mother-vessels.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('mother_vessel_id', $motherVessel->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



