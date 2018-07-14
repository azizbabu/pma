@extends('prints.master')

@section('title') Pending Purchase Requisition Report @endsection 

@section('content')

<h2 class="font-weight-bold">Pending Purchase Requisition Report</h2>
<h3><strong>{{ $report_title }} </strong></h3>
    @include('reports.pending-purchase-requisition-table')
@endsection

@section('custom-style')

@endsection
