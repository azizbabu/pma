@extends('layouts.master')

@section('title') Report of Purchase @endsection 
@section('page_title') Report @endsection

@section('content')
<div class="card margin-top-20">
	<div class="card-header">
		<h4 class="card-title">Report of Purchase</h4>
	</div>
	<div class="card-body">
		{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                    	{!! Form::label('po_date', 'PO Date') !!}
                        {!! Form::text('po_date', request()->po_date, ['class' => 'form-control datepicker', 'placeholder' => 'Enter P.O date']) !!}
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
			        <div class="form-group">
			        	{!! Form::label('plant_id', 'Plant') !!}
			            {!! Form::select('plant_id', $plants, request()->plant_id, ['class' => 'form-control chosen-select']) !!}
			        </div>
			    </div>
            	<div class="col-lg-3 col-md-6">
			        <div class="form-group">
			        	{!! Form::label('item_group_id', 'Item Group') !!}
			            {!! Form::select('item_group_id', $itemGroups, request()->item_group_id, ['class' => 'form-control chosen-select']) !!}
			        </div>
			    </div>
			    <div class="col-lg-3 col-md-6">
			        <div class="form-group">
			        	{!! Form::label('source_type', 'Source Type') !!}
			            {!! Form::select('source_type', array_prepend(config('constants.item_source_types'), 'Select source type', ''), request()->source_type, ['class' => 'form-control chosen-select']) !!}
			        </div>
			    </div>
            </div>

            <div class="row">
            	<div class="col-lg-4 col-md-6">
                    <div class="form-group">
                    	<button class="btn btn-info" data-toggle="tooltip" title="Search"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                        <a href="{{ url()->current() }}" class="btn btn-default" data-toggle="tooltip" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
                    </div>
                </div>
            </div>
    	{!! Form::close() !!}

		<div class="table-responsive">
			<table class="table table-striped table-bordered">
			    <thead class="table-dark">
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
			    @if(!empty($purchaseOrders) && $purchaseOrders->isNotEmpty())
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
				@else
			    	<tr>
			        	<td colspan="7" align="center">No Record Found!</td>
			        </tr>
			    @endif
			    </tbody>
			</table>
		</div>
		
	</div><!-- end card-body -->

	@if(!empty($purchaseOrders))
	<div class="card-footer">
		<a href="{{ url()->current() }}/print?{{ $query_string }}" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a>
	</div>
	@endif
</div><!-- end card  -->
@endsection