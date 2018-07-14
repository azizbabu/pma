@extends('layouts.master')

@section('title') Create Mother Vessel @endsection 
@section('page_title') Mother Vessels @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'mother-vessels', 'role' => 'form', 'id'=>'mother-vessel-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Create Mother Vessel</h4>
			</div>
			<div class="card-body">
				@include('mother-vessels.form')
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


