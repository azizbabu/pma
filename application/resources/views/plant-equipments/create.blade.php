@extends('layouts.master')

@section('title') Create Plant Equipment @endsection 
@section('page_title') Plant Equipments @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'plant-equipments', 'role' => 'form', 'id'=>'plant-equipment-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Create Plant Equipment</h4>
			</div>
			<div class="card-body">
				@include('plant-equipments.form')
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


