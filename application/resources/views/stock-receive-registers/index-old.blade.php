@extends('layouts.master')

@section('title') List of Stock Receive Registers @endsection 
@section('page_title') Stock Receive Registers @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h4 class="card-title">
						List of Stock Receive Registers
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('stock-receive-registers/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
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
		                            {!! Form::text('receive_code', request()->receive_code, ['class' => 'form-control', 'placeholder' => 'Enter Receive Code']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            {!! Form::text('receive_date', request()->receive_date, ['class' => 'form-control datepicker', 'placeholder' => 'Enter Receive Date']) !!}
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
						        	<th width="9%">PR No</th>
						        	<th width="9%">Receive Code</th>
						            <th width="12%">Receive Date</th>
						            <th width="14%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($stockReceiveRegisters as $stockReceiveRegister)
						        <tr>
						            <td>{{ $stockReceiveRegister->plant->name }}</td>
						            <td>{{ $stockReceiveRegister->item->name }}</td>
						            <td>{{ strtoupper($stockReceiveRegister->po_number) }}</td>
						            <td>{{ strtoupper($stockReceiveRegister->item_pr_number) }}</td>
						            <td>{{ strtoupper($stockReceiveRegister->receive_code) }}</td>
						            <td>{{ Carbon::parse($stockReceiveRegister->receive_date)->format('d M, Y') }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('stock-receive-registers/' . $stockReceiveRegister->id) }}" data-toggle="tooltip" title="View stock receive register"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('stock-receive-registers/' . $stockReceiveRegister->id . '/edit') }}" data-toggle="tooltip" title="Edit stock receive register"><i class="fa fa-pencil"></i></a>

						                {{-- Approve/Uapprove --}}

										<a href="#" data-id="{{$stockReceiveRegister->id}}" data-action="{{ url('stock-receive-registers/change-approve-status') }}" data-message="Are you sure, You want to {{ $stockReceiveRegister->approved_by ? 'unapprove':'approve' }} this stock receive register?" class="btn btn-{{ $stockReceiveRegister->approved_by ? 'warning':'info' }} btn-xs alert-dialog" data-toggle="tooltip" title="{{ $stockReceiveRegister->approved_by ? 'Unapprove':'Approve' }} stock receive register"><i class="fa fa-{{ $stockReceiveRegister->approved_by ? 'thumbs-o-down':'thumbs-o-up' }} white"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$stockReceiveRegister->id}}" data-action="{{ url('stock-receive-registers/delete') }}" data-message="Are you sure, You want to delete this stock receive register?" class="btn btn-danger btn-xs alert-dialog" data-toggle="tooltip" title="Delete stock receive register"><i class="fa fa-trash white"></i></a>
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

				@if($stockReceiveRegisters->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $stockReceiveRegisters->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $stockReceiveRegisters->links() !!}
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