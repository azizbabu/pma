@extends('layouts.master')

@section('title') Edit Plant Equipment @endsection 
@section('page_title') Plant Equipments @endsection

@section('content')

<div class="container">
	{!! Form::model($plantEquipment, array('url' => 'plant-equipments', 'role' => 'form', 'id'=>'plant-equipment-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Plant Equipment</h4>
			</div>
			<div class="card-body">
				@include('plant-equipments.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('plant_equipment_id', $plantEquipment->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



