@extends('layouts.master')

@section('title') Create Engine Generation @endsection 
@section('page_title') Engine Generations @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'engine-generations', 'role' => 'form', 'id'=>'engine-generation-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Create Engine Generation</h3>
			</div>
			<div class="card-body">
				@include('engine-generations.form')
			</div>
			<div class="card-footer">
				<button type="button" class="btn btn-info" onclick="addEngineGenerations();"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


