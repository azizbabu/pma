@extends('layouts.public_master')

@section('title') 403 @endsection

@section('content')
<div class="ex-page-content text-center">
	<h1 class="">403!</h1>
    <h3>You do not have enough permission to take this action.</a></h3>
    <a class="btn btn-info mb-5 waves-effect waves-light m-t-20" href="{{ url('/home') }}">Back to Dashboard</a>
</div>
@endsection