@extends('layouts.master')

@section('title') Create Mother Vessel Carring @endsection 
@section('page_title') Mother Vessel Carrings @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'mother-vessel-carrings', 'role' => 'form', 'id'=>'mother-vessel-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Create Mother Vessel Carring</h4>
			</div>
			<div class="card-body">
				@include('mother-vessel-carrings.form')
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


