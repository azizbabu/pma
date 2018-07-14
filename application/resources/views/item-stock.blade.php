@extends('layouts.master')

@section('title') Item Stock @endsection 
@section('page_title') Item Stock @endsection

@section('content')
<div class="card margin-top-20">
	<div class="card-header clearfix">
		<h4 class="card-title">List of Item Stock</h4>
	</div>

	<div class="card-body">
		
		{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
            <div class="row">
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
			        	{!! Form::label('name', 'Item Name') !!}
			            {!! Form::text('name', request()->name, ['class' => 'form-control', 'placeholder' => 'Enter name']) !!}
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
				<div class="col-lg-3 col-md-6">
			        <div class="form-group">
			        	{!! Form::label('item_moving', 'Item Moving') !!}
			            {!! Form::select('item_moving', config('constants.item_moving'), request()->item_moving, ['class' => 'form-control chosen-select']) !!}
			        </div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="form-group">
						{!! Form::label('moving_time', 'Moving Time') !!}
						{!! Form::select('moving_time', config('constants.moving_time'), request()->moving_time, ['class' => 'form-control chosen-select']) !!}
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="form-group">
						{!! Form::label('slow_moving_qty', 'Slow Moving Qty') !!}
						{!! Form::text('slow_moving_qty', request()->slow_moving_qty, ['class' => 'form-control', 'placeholder' => 'Enter Moving Qty']) !!}
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="form-group">
						{!! Form::label('stock_type', 'Stock Type') !!}
						{!! Form::select('stock_type', config('constants.item_stock_types'), request()->stock_type, ['class' => 'form-control chosen-select']) !!}
					</div>
				</div>
			</div>

			<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                    	<button class="btn btn-info" data-toggle="tooltip" title="Search"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                        <a href="{{ url()->current() }}" class="btn btn-default" data-toggle="tooltip" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
                    </div>
                </div>
            </div>

    	{!! Form::close() !!}
		
		<div class="table-responsive">
			<table class="table table-striped table-bordered">
			    <thead>
			        <tr>
			        	<th width="7%">Code</th>
			        	<th width="7%">Type</th>
			        	<th width="18%">Name</th>
			        	<th>Avg Price</th>
			            <th width="9%"><span data-toggle="tooltip" title="Safety Stock Qty">Safety Stock</span></th>
			            <th width="9%"><span data-toggle="tooltip" title="Opening Qty/Receiving Qty">Opn/Rcv Qty</span></th>
			            <th width="9%">Issue Qty</th>
			            <th width="9%">Pre. Stock Qty</th>
			            <th width="9%">Pre. Stock Value</th>
			            <th width="7%">Remarks</th>
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
		</div>
	</div><!-- end card-body -->
	@if(request()->all())
	<div class="card-footer">
		<a href="{{ url()->current() }}/print?{{ $query_string }}" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a>
	</div>
	@endif
</div><!-- end card  -->
@endsection