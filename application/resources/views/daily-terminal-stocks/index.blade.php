@extends('layouts.master')

@section('title') List of Daily Transaction Stock @endsection 
@section('page_title') Daily Transaction Stocks @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h3 class="card-title">
						List of Daily Transaction Stock
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('daily-terminal-stocks/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
					</h3>
				</div>

				<div class="card-body">
					
					{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
		                <div class="row">
		                    <div class="col-md-4">
		                        <div class="form-group">
		                            {!! Form::text('transaction_date', request()->transaction_date, ['class'=>'form-control datepicker','id' => 'transaction_date', 'placeholder' => 'Enter Transaction date']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-4">
		                        <div class="form-group">
		                            {!! Form::select('terminal_id', $terminals, request()->terminal_id, ['class' => 'form-control chosen-select']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-4">
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
						        	<th width="16%">Transaction Date</th>
						        	<th>Terminal</th>
						            <th width="7%">Tank</th>
						            <th width="14%">Tank Stock</th>
						            <th width="18%">Created at</th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($dailyTerminalStocks as $dailyTerminalStock)
						        <tr>
						        	<td>{{ Carbon::parse($dailyTerminalStock->transaction_date)->format('d M,Y') }}</td>
						            <td>{{ $dailyTerminalStock->terminal->name }}</td>
						            <td>{{ strtoupper($dailyTerminalStock->tank_number) }}</td>
						            <td>{{ $dailyTerminalStock->tank_stock }}</td>
						            <td>{{ $dailyTerminalStock->created_at->format('d M, Y H:i a') }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('daily-terminal-stocks/' . $dailyTerminalStock->id) }}" title="View transaction stock"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('daily-terminal-stocks/' . $dailyTerminalStock->id . '/edit') }}" title="Edit transaction stock"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$dailyTerminalStock->id}}" data-action="{{ url('daily-terminal-stocks/delete') }}" data-message="Are you sure, You want to delete this transaction stock?" class="btn btn-danger btn-xs alert-dialog" title="Delete transaction stock"><i class="fa fa-trash white"></i></a>
						            </td>
						        </tr>
						    @empty
						    	<tr>
						        	<td colspan="6" align="center">No Record Found!</td>
						        </tr>
						    @endforelse
						    </tbody>
						</table>
					</div>
				</div><!-- end card-body -->

				@if($dailyTerminalStocks->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $dailyTerminalStocks->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $dailyTerminalStocks->links() !!}
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