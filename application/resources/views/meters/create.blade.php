@extends('layouts.master')

@section('title') Create Meter @endsection 
@section('page_title') Meters @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'meters', 'role' => 'form', 'id'=>'meter-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Create Meter</h4>
			</div>
			<div class="card-body">
				@include('meters.form')
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


