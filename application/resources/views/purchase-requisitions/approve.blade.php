@extends('layouts.master')

@if(!$purchaseRequisition->approved_by)
    @php 
    $status = 'Approve';
    $icon_class = 'fa-thumbs-o-up'; 
    @endphp
@else
    @php 
    $status = 'Unapprove'; 
    $icon_class = 'fa-thumbs-o-down';
    @endphp
@endif

@section('title') {{ $status }} Purchase Requisition @endsection 
@section('page_title') Purchase Requisitions @endsection 

@section('content')
{!! Form::open(['url' => request()->url(), 'role' => 'form']) !!}
    <div class="card">
    	<div class="card-header">
    		<h4 class="card-title">{{ $status }} Purchase Requisition</h4>
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
            				<td>
                                @php $item = $purchaseRequisition->item @endphp
                                {{ $item->name }}
                                {!! Form::hidden('purchase_requisition_id[]', $purchaseRequisition->id) !!}
                            </td>
            				<td class="text-right">৳{{ number_format($purchaseRequisition->item_avg_price, 2) }}</td>
            				<td class="text-right">{{ $purchaseRequisition->item_safety_stock_qty }}</td>
            				<td class="text-right">{{ $purchaseRequisition->present_stock_qty }}</td>
            				<td class="text-right">{{ $purchaseRequisition->pipeline_qty }}</td>
            				<td class="text-right">
                                {{ $purchaseRequisition->required_qty }}
                            </td>
            				<td class="text-right">
                                {!! Form::number('approved_qty[]', $purchaseRequisition->approved_qty, ['class' => 'form-control', 'data-parsley-min' => 0, 'min' => 0, 'data-parsley-max' => $purchaseRequisition->required_qty, 'max' => $purchaseRequisition->required_qty, 'required']) !!}
                            </td>
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
    		<button class="btn btn-default btn-sm"><i class="fa {{ $icon_class }}"></i> {{ $status }}</button>
    		<a class="btn btn-info btn-sm" href="{{ URL::to('purchase-requisitions/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
    	</div>
    </div>
{!! Form::close() !!}
@endsection

@section('custom-script')
{!! Html::script($assets .'/plugins/parsleyjs/parsley.min.js') !!}
<script>
function changeApproveStatus(obj)
{
    var elem = $(obj);
    var id = elem.attr('data-id');
    var action = elem.attr('data-action');
    var tableRow = elem.closest('tr');
    var plant = tableRow.find('td:nth-child(1)').text();
    var item = tableRow.find('td:nth-child(2)').text();
    var reqCode = tableRow.find('td:nth-child(3)').text();
    var reqDate = tableRow.find('td:nth-child(4)').text();
    var status = tableRow.find('td:nth-child(6) span').text().trim();
    var presentStockQty = tableRow.find('input[name=present_stock_qty]').val();
    var requiredQty = tableRow.find('input[name=required_qty]').val();
    var approvedQty = tableRow.find('input[name=approved_qty]').val();
    
    $('#approval-modal form').attr('action', action);
    $('#approval-modal input[name=hdnResource]').val(id);
    $('#approved_qty').val(approvedQty);
    

    if(status == 'Pending') {
        $('#approval-modal .modal-title').text('Approve Purchase Requisition');
        $('#approval-modal #plant-name').text(plant);
        $('#approval-modal #item-name').text(item);
        $('#approval-modal #req-code').text(reqCode);
        $('#approval-modal #req-date').text(reqDate);
        $('#approval-modal #present-stock-qty').text(presentStockQty);
        $('#approval-modal #required-qty').text(requiredQty);
        $('#approved_qty').prop('disabled', false);
        $('#approved_qty').attr('data-parsley-min', 1);
        $('#approved_qty').attr('data-parsley-max', requiredQty);
        $('#approved_qty').attr('required', '');
    }else {
        $('#approval-modal .modal-title').text('Unapprove Purchase Requisition');
        $('#approval-modal #plant-name').text(plant);
        $('#approval-modal #item-name').text(item);
        $('#approval-modal #req-code').text(reqCode);
        $('#approval-modal #req-date').text(reqDate);
        $('#approval-modal #present-stock-qty').text(presentStockQty);
        $('#approval-modal #required-qty').text(requiredQty);
        $('#approved_qty').prop('disabled', true);
        $('#approved_qty').removeAttr('data-parsley-min');
        $('#approved_qty').removeAttr('data-parsley-max');
        $('#approved_qty').removeAttr('required');
    }

    $('#approval-modal').modal();
    $('#approval-modal-form').parsley();
}

(function() {
    $('#approval-modal-form').parsley();
})();
</script>
@endsection


