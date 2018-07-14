@extends('layouts.master')

@section('title') Edit Daily Plant Generation @endsection 
@section('page_title') Daily Plant Generations @endsection

@section('content')
{!! Form::model($dailyPlantGeneration, array('url' => 'daily-plant-generations', 'role' => 'form', 'id'=>'daily-plant-generation-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h3 class="card-title">Edit Daily Plant Generation</h3>
		</div>
		<div class="card-body">
			@include('daily-plant-generations.form')
		</div>
		<div class="card-footer">
			{!! Form::hidden('daily_plant_generation_id', $dailyPlantGeneration->id) !!}
			<button type="button" class="btn btn-info" onclick="addDailyPlantGenerations();"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
		</div>
	</div>		
{!! Form::close() !!}
@endsection




