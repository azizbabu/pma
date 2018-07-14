@extends('layouts.master')

@section('title') Edit Issue Register @endsection 
@section('page_title') Issue Registers @endsection

@section('content')

<div class="container">
	{!! Form::model($issueRegister, array('url' => 'issue-registers/'.$issueRegister->id, 'role' => 'form', 'id'=>'issue-register-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Issue Register</h4>
			</div>
			<div class="card-body">
				@include('issue-registers.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('issue_code', $issueRegister->issue_code) !!}
				<button type="button" class="btn btn-info" onclick="addIssueRegisters();"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



