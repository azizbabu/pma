@extends('layouts.master')

@section('title') Edit Engine @endsection 
@section('page_title') Engines @endsection

@section('content')

<div class="container">
	{!! Form::model($engine, array('url' => 'engines', 'role' => 'form', 'id'=>'engine-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Engine</h4>
			</div>
			<div class="card-body">
				@include('engines.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('engine_id', $engine->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



