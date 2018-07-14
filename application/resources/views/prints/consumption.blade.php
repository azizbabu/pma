@extends('prints.master')

@section('title') Consumtion Report @endsection 

@section('content')

<h2 class="font-weight-bold">Consumtion Report</h2>
<h3><strong>{{ $report_title }} </strong></h3>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th width="9%">Issue Code</th>
            <th width="12%">Issue Date</th>
            <th width="14%" class="text-right">Item Qty</th>
            <th width="12%" class="text-right">Required Qty</th>
            <th width="12%" class="text-right">Issue Qty</th>
            <th width="12%" class="text-right">Approve Qty</th>
        </tr>
    </thead>
    <tbody>
    @forelse($issueRegisters as $issueRegister)
        <tr>
            <td>{{ strtoupper($issueRegister->issue_code) }}</td>
            <td>{{ Carbon::parse($issueRegister->issue_date)->format('d M, Y') }}</td>
            <td class="text-right">{{ $issueRegister->item_qty }}</td>
            <td class="text-right">{{ $issueRegister->req_qty }}</td>
            <td class="text-right">{{ $issueRegister->issue_qty }}</td>
            <td class="text-right">{{ $issueRegister->apv_qty }}</td>
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
