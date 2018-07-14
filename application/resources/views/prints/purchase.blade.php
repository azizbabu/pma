@extends('prints.master')

@section('title') Purchase Report @endsection 

@section('content')

<h2 class="font-weight-bold">Purchase Report</h2>
<h3><strong>{{ $report_title }} </strong></h3>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th width="12%">P.O Number</th>
            <th width="15%">P.O Date</th>
            <th width="12%" class="text-right">Item Qty</th>
            <th width="12%" class="text-right">PR. Qty</th>
            <th width="12%" class="text-right">PO. Qty</th>
            <th width="12%" class="text-right">PO. Price</th>
            <th class="text-right">PO. Value</th>
        </tr>
    </thead>
    <tbody>
    @forelse($purchaseOrders as $purchaseOrder)
        <tr>
            <td>{{ strtoupper($purchaseOrder->po_number) }}</td>
            <td>{{ Carbon::parse($purchaseOrder->po_date)->format('d M, Y') }}</td>
            <td class="text-right">{{ $purchaseOrder->item_qty }}</td>
            <td class="text-right">{{ $purchaseOrder->pr_qty }}</td>
            <td class="text-right">{{ $purchaseOrder->po_qty }}</td>
            <td class="text-right">{{ $purchaseOrder->po_price }}</td>
            <td class="text-right">{{ $purchaseOrder->po_value }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" align="center">No Record Found!</td>
        </tr>
    @endforelse
    </tbody>
</table>
@endsection

@section('custom-style')

@endsection
