@extends('layouts.master')

@section('title') Create Item @endsection 
@section('page_title') Items @endsection 

@section('content')
<div class="container">
	{!! Form::open(array('url' => 'items', 'role' => 'form', 'id'=>'item-create-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Create Item</h4>
			</div>
			<div class="card-body">
				@include('items.form')
			</div>
			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection


