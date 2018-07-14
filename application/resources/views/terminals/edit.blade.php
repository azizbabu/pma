@extends('layouts.master')

@section('title') Edit Terminal @endsection 
@section('page_title') Terminals @endsection

@section('content')

{!! Form::model($terminal, array('url' => 'terminals', 'role' => 'form', 'id'=>'terminal-edit-form')) !!}
	<div class="card margin-top-20">
		<div class="card-header">
			<h4 class="card-title">Edit Terminal</h4>
		</div>
		<div class="card-body">
			@include('terminals.form')
		</div>
		<div class="card-footer">
			{!! Form::hidden('terminal_id', $terminal->id) !!}
			<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
		</div>
	</div>		
{!! Form::close() !!}

@endsection



