@extends('prints.master')

@section('title') Item Stock Report @endsection 

@section('content')

<h3><strong>{{ $report_title }} </strong></h3>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th width="7%">Code</th>
            <th width="7%">Type</th>
            <th width="18%">Name</th>
            <th>Avg Price</th>
            <th width="9%"><span data-toggle="tooltip" title="Safety Stock Qty">Safety Stock</span></th>
            <th width="9%"><span data-toggle="tooltip" title="Opening Qty/Receiving Qty">Open/Recv Qty</span></th>
            <th width="9%">Issue Qty</th>
            <th width="9%">Pre. Stock Qty</th>
            <th width="9%">Pre. Stock Value</th>
            <th width="12%">Remarks</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($items) && $items->isNotEmpty())
        @foreach($items as $item)
        <tr>
            <td>{{ strtoupper($item->code) }}</td>
            <td>{{ config('constants.item_source_types.'.$item->source_type) }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ number_format($item->avg_price,2) }}</td>
            <td>{{ $item->safety_stock_qty }}</td>
            <td>{{ $item->opening_receiving_qty }}</td>
            <td>{{ $item->issue_qty }}</td>
            <td>{{ $item->present_stock_qty }}</td>
            <td>{{ number_format(($item->avg_price * $item->present_stock_qty), 2) }}</td>
            <td>{{ $remarks }}</td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="10" align="center">No Record Found!</td>
        </tr>
        @endif
    </tbody>
</table>
@endsection

@section('custom-style')

@endsection
