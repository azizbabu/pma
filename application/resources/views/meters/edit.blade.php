@extends('layouts.master')

@section('title') Edit Meter @endsection 
@section('page_title') Meters @endsection

@section('content')

<div class="container">
	{!! Form::model($meter, array('url' => 'meters', 'role' => 'form', 'id'=>'meter-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Meter</h4>
			</div>
			<div class="card-body">
				@include('meters.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('meter_id', $meter->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



