@extends('prints.master')

@section('title') Plantwise Equipment Running Hour Report @endsection 

@section('content')

<div class="text-center">
    <h2><strong>Monthly Equipment Running Hour Summary</strong></h2>
    <h4><strong>{{ $plant->name }}</strong></h4> <br>
</div>

@if(request()->all())
    <div class="margin-top-20">
        @include('reports.plant-wise-equipment-running-hour-table')
    </div>      
@endif

@endsection

@section('custom-style')

@endsection
