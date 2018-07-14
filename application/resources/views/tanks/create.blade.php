@extends('layouts.master')

@section('title') Create Tank @endsection 
@section('page_title') Tanks @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'tanks', 'role' => 'form', 'id'=>'tank-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Create Tank</h4>
			</div>
			<div class="card-body">
				@include('tanks.form')
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


