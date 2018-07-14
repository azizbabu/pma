@extends('layouts.master')

@section('title') Create Terminal @endsection 
@section('page_title') Terminals @endsection 

@section('content')

{!! Form::open(array('url' => 'terminals', 'role' => 'form', 'id'=>'terminal-create-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h4 class="card-title">Create Terminal</h4>
		</div>
		<div class="card-body">
			@include('terminals.form')
		</div>
		<div class="card-footer">
			<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
		</div>
	</div>		
{!! Form::close() !!}

@endsection


