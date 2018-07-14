@extends('layouts.master')

@section('title') Report of Pending Purchase Requisition @endsection 
@section('page_title') Report of Pending Purchase Requisition  @endsection

@section('content')
<div class="card margin-top-20">
	<div class="card-header">
		<h4 class="card-title">Pending Purchase Requisition Report</h4>
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
			@include('reports.pending-purchase-requisition-table')
		</div>
	</div><!-- end card-body -->

	@if(!empty($purchaseRequisitions))
	<div class="card-footer">
		<a href="{{ url()->current() }}/print?{{ $query_string }}" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a>
	</div>
	@endif
</div><!-- end card  -->
@endsection