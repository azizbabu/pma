@extends('layouts.public_master')

@section('title') 404 @endsection

@section('content')
<div class="ex-page-content text-center">
    <h1 class="">404!</h1>
    <h3 class="">Sorry, page not found</h3><br>

    <a class="btn btn-info mb-5 waves-effect waves-light" href="{{ url('/home') }}">Back to Dashboard</a>
</div>
@endsection