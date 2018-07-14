@extends('layouts.master')

@section('title') Create Engine @endsection 
@section('page_title') Engines @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'engines', 'role' => 'form', 'id'=>'engine-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Create Engine</h4>
			</div>
			<div class="card-body">
				@include('engines.form')
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


