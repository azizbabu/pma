@extends('layouts.master')

@section('title') List of Fuel Tardes @endsection 
@section('page_title') Fuel Tardes @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h4 class="card-title">
						List of Fuel Trades
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('fuel-trades/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
					</h4>
					
				</div>

				<div class="card-body">
					
					{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
		                <div class="row">
		                	<div class="col-md-3">
		                        <div class="form-group">
		                            {!! Form::text('transaction_date', request()->transaction_date, ['class'=>'form-control datepicker','id' => 'transaction_date', 'placeholder' =>  'Enter Transaction date']) !!}
		                        </div>
		                    </div>
		                	<div class="col-md-3">
		                        <div class="form-group">
		                            {!! Form::select('party_id', $parties, request()->party_id, ['class' => 'form-control chosen-select']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-3">
		                        <div class="form-group">
		                            {!! Form::select('terminal_id', $terminals, request()->terminal_id, ['class' => 'form-control chosen-select']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-3">
		                        <div class="form-group">
		                        	<button class="btn btn-info" data-toggle="tooltip" title="Search"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
		                            <a href="{{ url()->current() }}" class="btn btn-default float-right" data-toggle="tooltip" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a>
		                        </div>
		                    </div>
		                </div>
                	{!! Form::close() !!}

					<div class="table-responsive">
						<table class="table table-striped table-bordered">
						    <thead>
						        <tr>
						        	<th width="14%">Transaction Date</th>
						        	<th>Party</th>
						        	<th>Terminal</th>
						        	<th width="9%"><span data-toggle="tooltip" title="Loan Given Qty">LGQ</span></th>
						        	<th width="9%"><span data-toggle="tooltip" title="Loan Receive Qty">LRVQ</span></th>
						        	<th width="9%"><span data-toggle="tooltip" title="Loan Return Qty">LRTQ</span></th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($fuelTrades as $fuelTrade)
						        <tr>
						        	<td>{{ Carbon::parse($fuelTrade->transaction_date)->format('d M, Y') }}</td>
						        	<td>{{ $fuelTrade->party->name }}</td>
						            <td>{{ $fuelTrade->terminal->name }}</td>
						            <td>{{ $fuelTrade->loan_given_qty }}</td>
						            <td>{{ $fuelTrade->loan_receive_qty }}</td>
						            <td>{{ $fuelTrade->loan_return_qty }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('fuel-trades/' . $fuelTrade->id) }}" title="View tank"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('fuel-trades/' . $fuelTrade->id . '/edit') }}" title="Edit tank"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$fuelTrade->id}}" data-action="{{ url('fuel-trades/delete') }}" data-message="Are you sure, You want to delete this tank?" class="btn btn-danger btn-xs alert-dialog" title="Delete tank"><i class="fa fa-trash white"></i></a>
						            </td>
						        </tr>

						    @empty
						    	<tr>
						        	<td colspan="5" align="center">No Record Found!</td>
						        </tr>
						    @endforelse
						    </tbody>
						</table>
					</div>
				</div><!-- end card-body -->

				@if($fuelTrades->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $fuelTrades->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $fuelTrades->links() !!}
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