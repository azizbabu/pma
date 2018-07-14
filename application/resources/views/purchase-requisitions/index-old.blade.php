@extends('layouts.master')

@section('title') List of Purchase Requisitions @endsection 
@section('page_title') Purchase Requisitions @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h4 class="card-title">
						List of Purchase Requisitions
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('purchase-requisitions/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
					</h4>
				</div>

				<div class="card-body">
					
					{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
		                <div class="row">
		                	<div class="col-md-3">
						        <div class="form-group">
						            {!! Form::select('plant_id', $plants, request()->plant_id, ['class' => 'form-control chosen-select']) !!}
						        </div>
						    </div>
						    <div class="col-md-3">
						        <div class="form-group">
						            {!! Form::select('item_id', $items, request()->item_id, ['class' => 'form-control chosen-select']) !!}
						        </div>
						    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            {!! Form::text('requisition_code', request()->requisition_code, ['class' => 'form-control', 'placeholder' => 'Enter Requisition Code']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            {!! Form::text('requisition_date', request()->requisition_date, ['class' => 'form-control datepicker', 'placeholder' => 'Enter Requisition Date']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                        	<button class="btn btn-info" data-toggle="tooltip" title="Search"><i class="fa fa-search" aria-hidden="true"></i></button>
		                            <a href="{{ url()->current() }}" class="btn btn-default float-right" data-toggle="tooltip" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a>
		                        </div>
		                    </div>
		                </div>
                	{!! Form::close() !!}

					<div class="table-responsive">
						<table class="table table-striped table-bordered">
						    <thead>
						        <tr>
						        	<th>Plant</th>
						        	<th width="14%">Item</th>
						        	<th width="9%"><span data-toggle="tooltip" title="Requisition Code">Req. Code</span></th>
						            <th width="12%"><span data-toggle="tooltip" title="Requisition Date">Req. Date</span></th>
						            <th width="12%" class="text-center"><span data-toggle="tooltip" title="Spare Parts Type">SPT</span></th>
						            <th width="12%" class="text-center">Status</th>
						            <th width="14%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($purchaseRequisitions as $purchaseRequisition)
						        <tr>
						            <td>{{ $purchaseRequisition->plant->name }}</td>
						            <td>{{ $purchaseRequisition->item->name }}</td>
						            <td>{{ strtoupper($purchaseRequisition->requisition_code) }}</td>
						            <td>{{ Carbon::parse($purchaseRequisition->requisition_date)->format('d M, Y') }}</td>
						            <td class="text-center">{{ config('constants.spare_parts_types.'.$purchaseRequisition->spare_parts_type) }}</td>
						            <td class="text-center"><span class="badge badge-{{ $purchaseRequisition->approved_by ? 'success':'warning' }}">{{ $purchaseRequisition->approved_by ? 'Approved':'Pending' }}</span></td>

						            <td class="action-column">
										{!! Form::hidden('present_stock_qty', $purchaseRequisition->present_stock_qty) !!}
										{!! Form::hidden('required_qty', $purchaseRequisition->required_qty) !!}
										{!! Form::hidden('approved_qty', $purchaseRequisition->approved_qty) !!}
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('purchase-requisitions/' . $purchaseRequisition->id) }}" data-toggle="tooltip" title="View purchase requisition"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('purchase-requisitions/' . $purchaseRequisition->id . '/edit') }}" data-toggle="tooltip" title="Edit purchase requisition"><i class="fa fa-pencil"></i></a>

						                {{-- Approve/Uapprove --}}

										<a data-id="{{$purchaseRequisition->id}}" data-action="{{ url('purchase-requisitions/change-approve-status') }}" data-message="Are you sure, You want to {{ $purchaseRequisition->approved_by ? 'unapprove':'approve' }} this purchase requisition?" class="btn btn-{{ $purchaseRequisition->approved_by ? 'warning':'info' }} btn-xs" data-toggle="tooltip" title="{{ $purchaseRequisition->approved_by ? 'unapprove':'approve' }} purchase requisition" onclick="changeApproveStatus(this);"><i class="fa fa-{{ $purchaseRequisition->approved_by ? 'thumbs-o-down':'thumbs-o-up' }} white"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$purchaseRequisition->id}}" data-action="{{ url('purchase-requisitions/delete') }}" data-message="Are you sure, You want to delete this purchase requisition?" class="btn btn-danger btn-xs alert-dialog" data-toggle="tooltip" title="Delete purchase requisition"><i class="fa fa-trash white"></i></a>
						            </td>
						        </tr>

						    @empty
						    	<tr>
						        	<td colspan="7" align="center">No Record Found!</td>
						        </tr>
						    @endforelse
						    </tbody>
						</table>
					</div>
				</div><!-- end card-body -->

				@if($purchaseRequisitions->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $purchaseRequisitions->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $purchaseRequisitions->links() !!}
							</div>
						</div>
					</div>
				</div>
				@endif
			</div><!-- end card  -->
		</div>
	</div>
</div>

{{-- Approval Modal --}}
<div id="approval-modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
  	{!! Form::open(['url' => '', 'role' => 'form', 'id' => 'approval-modal-form']) !!}
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Modal title</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <div class="details-info">
	         	<strong>Plant: </strong><span id="plant-name"></span><br>
	         	<strong>Item: </strong><span id="item-name"></span><br>
	         	<strong>Req. Code: </strong><span id="req-code"></span><br>
	         	<strong>Req. Date: </strong><span id="req-date"></span><br>
	         	<strong>Present Stock Qty: </strong><span id="present-stock-qty"></span><br>
	         	<strong>Required Qty: </strong><span id="required-qty"></span><br>
	         	<div class="form-group mt-2">
		         	{!! Form::label('approved_qty', 'Approved Qty') !!}
		         	<label for="approved_qty" class="control-label">
		         		{!! validation_error($errors->first('approved_qty'),'approved_qty') !!}
		         	</label>
		         	{!! Form::text('approved_qty', null, ['class' => 'form-control', 'placeholder' => 'Enter Approved Qty', 'data-parsley-min' => 1, 'data-parsley-max' => 1, 'id' => 'approved_qty', 'required']) !!}
	         	</div>
	         </div>
	      </div>
	      <div class="modal-footer">
	      	{!! Form::hidden('hdnResource', '') !!}
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
	        <button class="btn btn-primary">Yes</button>
	      </div>
	    </div>
    {!! Form::close() !!}
  </div>
</div>
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