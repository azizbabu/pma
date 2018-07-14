@extends('layouts.master')

@section('title') Edit Plant @endsection 
@section('page_title') Plants @endsection

@section('content')

<div class="container">
	{!! Form::model($plant, array('url' => 'plants', 'role' => 'form', 'id'=>'plant-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Plant</h4>
			</div>
			<div class="card-body">
				@include('plants.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('plant_id', $plant->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



