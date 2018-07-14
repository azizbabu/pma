@extends('layouts.master')

@section('title') Purchase Order Details @endsection 
@section('page_title') Purchase Orders @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Purchase Order Details</h4>
	</div>
	<div class="card-body">
		<div class="details-info">
        	<div class="row">
        		<div class="col-sm-6">
        			<strong>Plant: </strong>{{ $purchaseOrder->plant->name }} <br>
        			<strong>Spare Parts Type:</strong> {{ config('constants.spare_parts_types.'.$purchaseOrder->spare_parts_type) }} <br>
        			<strong>Source Type:</strong> {{ config('constants.po_source_types.'.$purchaseOrder->source_type) }} <br>
        		</div>
        		<div class="col-sm-6">
        			<strong>P.O Number:</strong> {{ strtoupper($purchaseOrder->po_number) }} <br>
        			<strong>P.O Date:</strong> {{ Carbon::parse($purchaseOrder->po_date)->format('d M, Y') }} <br>
        		</div>
        	</div>
        </div>

        <div class="table-responsive margin-top-20">
		    <table class="table table-hover table-striped table-vertical-middle table-sm">
		        <thead class="thead-dark">
		            <tr>
		                <th width="12%">PR. Code</th>
		                <th>Item</th>
		                <th width="10%" class="text-right">Avg. Price</th>
		                <th width="12%" class="text-right">Last Price</th>
		                <th width="10%" class="text-right">PR Qty</th>
		                <th width="12%" class="text-right">PR Value</th>
		                <th width="10%" class="text-right">PO Qty</th>
		                <th width="10%" class="text-right">PO Price</th>
		                <th width="12%" class="text-right">PO Value</th>
		                <th width="15%" >Remarks</th>
		                <th width="5%"></th>
		            </tr>
		        </thead>
		        <tbody>
		        	@php 
						$total_pr_value = 0;
						$total_po_value = 0;
		        	@endphp
		        	@foreach($purchaseOrders as $purchaseOrder)
		        	
		        	@php $item = $purchaseOrder->item @endphp
		            <tr>
		                <td>{{ $purchaseOrder->requisition_code }}</td>
		                <td>{{ $item->name }}</td>
		                <td class="text-right">৳{{ number_format($item->avg_price,2) }}</td>
		                <td class="text-right">
		                    ৳ {{ number_format($purchaseOrder->last_price,2) }}
		                </td>
		                <td class="text-right">{{ $purchaseOrder->pr_qty }}</td>
		                @php
						$pr_value = 	$purchaseOrder->pr_qty*$item->avg_price;
						$total_pr_value += $pr_value
		                @endphp
		                <td class="text-right">৳{{ number_format($pr_value, 2) }}</td>
		                <td class="text-right">{{ $purchaseOrder->po_qty }}</td>
		                
		                <td class="text-right">৳{{ number_format($purchaseOrder->po_price,2) }}</td>
		                @php
							$po_value = $purchaseOrder->po_qty * $purchaseOrder->po_price;
							$total_po_value += $po_value
		                @endphp
		                <td class="text-right">৳{{ number_format($po_value,2) }}</td>
		                <td>{{ $purchaseOrder->remarks }}</td>
		            </tr>
		            @endforeach
		        </tbody>
		        <tfoot>
		            <tr id="total-row" class="font-weight-bold">
		                <td colspan="5" class="text-right">Total</td>
		                <td id="total-pr-value" class="text-right">৳{{ number_format($total_pr_value,2) }}</td>
		                <td class="text-right"></td>
		                <td class="text-right"></td>
		                <td id="total-po-value" class="text-right">৳{{ number_format($total_po_value,2) }}</td>
		                <td colspan="2"></td>
		            </tr>
		        </tfoot>
		    </table>
		</div>
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('purchase-orders/' . $purchaseOrder->po_number . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('purchase-orders/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


