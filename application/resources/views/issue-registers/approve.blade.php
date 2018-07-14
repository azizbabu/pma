@extends('layouts.master')

@if(!$issueRegister->approved_by)
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
            			<strong>Plant: </strong>{{ $issueRegister->plant->name }} <br>
    		        	<strong>Spare Parts Type:</strong> {{ config('constants.spare_parts_types.'.$issueRegister->spare_parts_type) }} <br>
    		        	<strong>Source Type:</strong> {{ config('constants.po_source_types.'.$issueRegister->source_type) }} <br>
            		</div>
            		<div class="col-md-6">
            			<strong>Requisition Code:</strong> {{ strtoupper($issueRegister->requisition_code) }} <br>
    		        	<strong>Requisition Date:</strong> {{ Carbon::parse($issueRegister->requisition_date)->format('d M, Y') }} <br>
            		</div>
            	</div>
            </div>

            <div class="table-responsive margin-top-20">
            	<table class="table table-striped">
            		<thead class="thead-dark">
            			<tr>
            				<th>Item</th>
                            <th width="12%" class="text-right">Avg. Price</th>
                            <th width="12%" class="text-right">Safety Stock</th>
                            <th width="12%" class="text-right"><span data-toggle="tooltip" title="Balance Stock">Bal. Stock</span></th>
                            <th width="10%" class="text-right"><span data-toggle="tooltip" title="Required Qty" class="text-right">Req. Qty</span></th>
                            <th width="10%" class="text-right"><span data-toggle="tooltip" title="Approved Qty" class="text-right">Apv. Qty</span></th>
                            <th width="10%" class="text-right">Issue Qty</th>
                            <th width="15%" >Remarks</th>
            			</tr>
            		</thead>
            		<tbody>
            		@foreach($issueRegisters as $issueRegister)
            			<tr>
            				<td>
                                @php $item = $issueRegister->item @endphp
                                {{ $item->name }}
                                {!! Form::hidden('issue_register_id[]', $issueRegister->id) !!}
                            </td>
            				<td class="text-right">
                                ৳{{ number_format($item->avg_price, 2) }}
                            </td>
                            <td class="text-right">{{ $issueRegister->item_safety_stock_qty }}</td>
                            <td class="text-right">{{ $issueRegister->balance_stock_qty }}</td>
                            <td class="text-right">{{ $issueRegister->required_qty }}</td>
                            <td class="text-right">
                                {!! Form::number('approved_qty[]', $issueRegister->approved_qty, ['class' => 'form-control', 'data-parsley-min' => 0, 'min' => 0, 'data-parsley-max' => $issueRegister->required_qty, 'max' => $issueRegister->required_qty, 'required']) !!}
                            </td>
                            <td class="text-right">
                                {{ $issueRegister->issue_qty }}
                            </td>
            				<td>{{ $issueRegister->remarks? $issueRegister->remarks : 'N/A' }}</td>
            			</tr>
            		@endforeach
            		</tbody>
            	</table>
            </div>
    	</div>
    	<div class="card-footer">
    		<button class="btn btn-default btn-sm"><i class="fa {{ $icon_class }}"></i> {{ $status }}</button>
    		<a class="btn btn-info btn-sm" href="{{ URL::to('issue-registers/list') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
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


