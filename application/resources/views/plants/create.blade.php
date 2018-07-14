@extends('layouts.master')

@section('title') Create Plant @endsection 
@section('page_title') Plants @endsection 

@section('content')

{!! Form::open(array('url' => 'plants', 'role' => 'form', 'id'=>'plant-create-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h4 class="card-title">Create Plant</h4>
		</div>
		<div class="card-body">
			@include('plants.form')
		</div>
		<div class="card-footer">
			<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
		</div>
	</div>		
{!! Form::close() !!}

@endsection


