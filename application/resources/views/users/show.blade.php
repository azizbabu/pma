@extends('layouts.master')

@section('title') User Details @endsection 
@section('page_title') Users @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h3 class="card-title">User Details</h3>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<h4><strong>Name: </strong>{{ $user->name }}</h4>
		        	<strong>Email:</strong> {{ $user->email }} <br>
		        	<strong>Phone:</strong> {{ $user->phone ? $user->phone : 'N/A' }} <br>
		        	<strong>Created at:</strong> {{ $user->created_at->format('d M, Y H:i A') }}
		        </div>
			</div>
		</div>	
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('users/' . $user->id . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('users/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


