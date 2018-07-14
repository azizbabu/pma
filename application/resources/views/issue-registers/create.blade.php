@extends('layouts.master')

@section('title') Create Issue Register @endsection 
@section('page_title') Issue Registers @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'issue-registers', 'role' => 'form', 'id'=>'issue-register-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Create Issue Register</h4>
			</div>
			<div class="card-body">
				@include('issue-registers.form')
			</div>
			<div class="card-footer">
				<button type="button" class="btn btn-info" onclick="addIssueRegisters();"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


