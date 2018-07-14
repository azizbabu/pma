@extends('layouts.master')

@section('title') Edit Item @endsection 
@section('page_title') Items @endsection

@section('content')

<div class="container">
	{!! Form::model($item, array('url' => 'items', 'role' => 'form', 'id'=>'item-edit-form')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h4 class="card-title">Edit Item</h4>
			</div>
			<div class="card-body">
				@include('items.form')
			</div>
			<div class="card-footer">
				{!! Form::hidden('item_id', $item->id) !!}
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection



