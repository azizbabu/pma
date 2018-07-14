@extends('layouts.master')

@section('title') List of Purchase Orders @endsection 
@section('page_title') Purchase Orders @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h4 class="card-title">
						List of Purchase Orders
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('purchase-orders/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
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
		                            {!! Form::text('po_number', request()->po_number, ['class' => 'form-control', 'placeholder' => 'Enter P.O number']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            {!! Form::text('po_date', request()->po_date, ['class' => 'form-control datepicker', 'placeholder' => 'Enter P.O date']) !!}
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
						        	<th width="9%">P.O Number</th>
						            <th width="12%">P.O Date</th>
						            <th width="12%" class="text-center"><span data-toggle="tooltip" title="Spare Parts Type">SPT</span></th>
						            <th width="12%" class="text-center">Source Type</th>
						            <th width="14%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($purchaseOrders as $purchaseOrder)
						        <tr>
						            <td>{{ $purchaseOrder->plant->name }}</td>
						            <td>{{ $purchaseOrder->item->name }}</td>
						            <td>{{ strtoupper($purchaseOrder->po_number) }}</td>
						            <td>{{ Carbon::parse($purchaseOrder->po_date)->format('d M, Y') }}</td>
						            <td class="text-center">{{ config('constants.spare_parts_types.'.$purchaseOrder->spare_parts_type) }}</td>
						            <td class="text-center">{{ config('constants.po_source_types.'.$purchaseOrder->source_type) }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('purchase-orders/' . $purchaseOrder->id) }}" title="View purchase order"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('purchase-orders/' . $purchaseOrder->id . '/edit') }}" title="Edit purchase order"><i class="fa fa-pencil"></i></a>

						                {{-- Approve/Uapprove --}}

										<a href="#" data-id="{{$purchaseOrder->id}}" data-action="{{ url('purchase-orders/change-approve-status') }}" data-message="Are you sure, You want to {{ $purchaseOrder->approved_by ? 'unapprove':'approve' }} this purchase order?" class="btn btn-{{ $purchaseOrder->approved_by ? 'warning':'info' }} btn-xs alert-dialog" title="{{ $purchaseOrder->approved_by ? 'unapprove':'approve' }} purchase order"><i class="fa fa-{{ $purchaseOrder->approved_by ? 'thumbs-o-down':'thumbs-o-up' }} white"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$purchaseOrder->id}}" data-action="{{ url('purchase-orders/delete') }}" data-message="Are you sure, You want to delete this purchase order?" class="btn btn-danger btn-xs alert-dialog" title="Delete purchase order"><i class="fa fa-trash white"></i></a>
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

				@if($purchaseOrders->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $purchaseOrders->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $purchaseOrders->links() !!}
							</div>
						</div>
					</div>
				</div>
				@endif
			</div><!-- end card  -->
		</div>
	</div>
</div>
@endsection