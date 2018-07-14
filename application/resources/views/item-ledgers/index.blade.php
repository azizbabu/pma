@extends('layouts.master')

@section('title') List of Item Ledgers @endsection 
@section('page_title') Item Ledgers @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h4 class="card-title">
						List of Item Ledgers
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('item-ledgers/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
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
		                            {!! Form::text('issue_code', request()->issue_code, ['class' => 'form-control', 'placeholder' => 'Enter issue code']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            {!! Form::text('issue_date', request()->issue_date, ['class' => 'form-control datepicker', 'placeholder' => 'Enter issue date']) !!}
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
						        	<th width="9%">Issue Code</th>
						            <th width="14%">Issue Date</th>
						            <th width="12%">Received Qty</th>
						            <th width="12%">Issue Qty</th>
						            <th width="14%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($itemLedgers as $itemLedger)
						        <tr>
						            <td>{{ $itemLedger->plant->name }}</td>
						            <td>{{ $itemLedger->item->name }}</td>
						            <td>{{ strtoupper($itemLedger->issue_code) }}</td>
						            <td>{{ Carbon::parse($itemLedger->issue_date)->format('d M, Y') }}</td>
						            <td>{{ $itemLedger->receive_qty   }}</td>
						            <td>{{ $itemLedger->issue_qty }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('item-ledgers/' . $itemLedger->id) }}" title="View item ledger"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('item-ledgers/' . $itemLedger->id . '/edit') }}" title="Edit item ledger"><i class="fa fa-pencil"></i></a>

						                {{-- Approve/Uapprove --}}

										<a href="#" data-id="{{$itemLedger->id}}" data-action="{{ url('item-ledgers/change-approve-status') }}" data-message="Are you sure, You want to {{ $itemLedger->approved_by ? 'unapprove':'approve' }} this item ledger?" class="btn btn-{{ $itemLedger->approved_by ? 'warning':'info' }} btn-xs alert-dialog" title="{{ $itemLedger->approved_by ? 'unapprove':'approve' }} item ledger"><i class="fa fa-{{ $itemLedger->approved_by ? 'thumbs-o-down':'thumbs-o-up' }} white"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$itemLedger->id}}" data-action="{{ url('item-ledgers/delete') }}" data-message="Are you sure, You want to delete this item ledger?" class="btn btn-danger btn-xs alert-dialog" title="Delete item ledger"><i class="fa fa-trash white"></i></a>
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

				@if($itemLedgers->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $itemLedgers->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $itemLedgers->links() !!}
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