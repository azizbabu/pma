@extends('layouts.master')

@section('title') Edit Item Type @endsection 
@section('page_title') Item Types @endsection

@section('content')

<div class="container">
	{!! Form::model($itemGroup, array('url' => 'item-groups', 'role' => 'form', 'id'=>'item-group-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Item Type</h4>
			</div>
			<div class="card-body">
				@include('item-groups.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('item_group_id', $itemGroup->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



