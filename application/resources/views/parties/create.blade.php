@extends('layouts.master')

@section('title') Create Party @endsection 
@section('page_title') Parties @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'parties', 'role' => 'form', 'id'=>'party-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Create Party</h4>
			</div>
			<div class="card-body">
				@include('parties.form')
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


