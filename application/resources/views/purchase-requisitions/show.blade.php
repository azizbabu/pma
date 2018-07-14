@extends('layouts.master')

@section('title') Purchase Requisition Details @endsection 
@section('page_title') Purchase Requisitions @endsection 

@section('content')
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Purchase Requisition Details</h4>
	</div>
	<div class="card-body">
		<div class="details-info">
        	<div class="row">
        		<div class="col-md-6">
        			<strong>Plant: </strong>{{ $purchaseRequisition->plant->name }} <br>
		        	<strong>Spare Parts Type:</strong> {{ config('constants.spare_parts_types.'.$purchaseRequisition->spare_parts_type) }} <br>
		        	<strong>Source Type:</strong> {{ config('constants.po_source_types.'.$purchaseRequisition->source_type) }} <br>
        		</div>
        		<div class="col-md-6">
        			<strong>Requisition Code:</strong> {{ strtoupper($purchaseRequisition->requisition_code) }} <br>
		        	<strong>Requisition Date:</strong> {{ Carbon::parse($purchaseRequisition->requisition_date)->format('d M, Y') }} <br>
        		</div>
        	</div>
        </div>

        <div class="table-responsive margin-top-20">
        	<table class="table table-striped">
        		<thead class="thead-dark">
        			<tr>
        				<th>Item</th>
        				<th width="10%" class="text-right">Avg. Price</th>
		                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Safety Stock Qty">Saf. Stock</span></th>
		                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Present Stock Qty">Pre. Stock</span></th>
		                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Pipeline Qty">Pip. Qty</span></th>
		                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Required Qty">Req. Qty</span></th>
		                <th width="10%" class="text-right"><span data-toggle="tooltip" title="Approved Qty">Apv. Qty</span></th>
		                <th width="12%" class="text-right">Total Value</th>
		                <th width="15%" >Remarks</th>
        			</tr>
        		</thead>
        		<tbody>
        		@foreach($purchaseRequisitions as $purchaseRequisition)
        			<tr>
        				<td>{{ $purchaseRequisition->item->name }}</td>
        				<td class="text-right">৳{{ number_format($purchaseRequisition->item_avg_price, 2) }}</td>
        				<td class="text-right">{{ $purchaseRequisition->item_safety_stock_qty }}</td>
        				<td class="text-right">{{ $purchaseRequisition->present_stock_qty }}</td>
        				<td class="text-right">{{ $purchaseRequisition->pipeline_qty }}</td>
        				<td class="text-right">{{ $purchaseRequisition->required_qty }}</td>
        				<td class="text-right">{{ $purchaseRequisition->approved_qty }}</td>
        				<td class="text-right">৳{{ number_format($purchaseRequisition->total_value, 2) }}</td>
        				<td>{{ $purchaseRequisition->remarks }}</td>
        			</tr>
        		@endforeach
        		</tbody>
        		<tfoot>
        			<tr>
        				<td class="text-right" colspan="6"></td>
        				<td class="text-right"></td>
        				<td></td>
        			</tr>
        		</tfoot>
        	</table>
        </div>
	</div>
	<div class="card-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('purchase-requisitions/' . $purchaseRequisition->requisition_code . '/edit') }}"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('purchase-requisitions/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


