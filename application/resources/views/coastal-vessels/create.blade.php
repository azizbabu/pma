@extends('layouts.master')

@section('title') Create Coastal Vessel @endsection 
@section('page_title') Coastal Vessels @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'coastal-vessels', 'role' => 'form', 'id'=>'coastal-vessel-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Create Coastal Vessel</h4>
			</div>
			<div class="card-body">
				@include('coastal-vessels.form')
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


