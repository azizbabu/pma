@extends('layouts.master')

@section('title') Create Coastal Vessel Carring @endsection 
@section('page_title') Coastal Vessel Carrings @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'coastal-vessel-carrings', 'role' => 'form', 'id'=>'coastal-vessel-carring-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Create Coastal Vessel Carring</h4>
			</div>
			<div class="card-body">
				@include('coastal-vessel-carrings.form')
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


