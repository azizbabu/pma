@extends('layouts.master')

@section('title') Create Item Type @endsection 
@section('page_title') Item Types @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'item-groups', 'role' => 'form', 'id'=>'fuel-type-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Create Item Type</h4>
			</div>
			<div class="card-body">
				@include('item-groups.form')
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


