@extends('layouts.master')

@section('title') Edit Party @endsection 
@section('page_title') Parties @endsection

@section('content')

<div class="container">
	{!! Form::model($party, array('url' => 'parties', 'role' => 'form', 'id'=>'party-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Party</h4>
			</div>
			<div class="card-body">
				@include('parties.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('party_id', $party->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



