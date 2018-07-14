@extends('layouts.master')

@section('title') List of Issue Registers @endsection 
@section('page_title') Issue Registers @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h4 class="card-title">
						List of Issue Registers
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('issue-registers/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
					</h4>
				</div>

				<div class="card-body">

					{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'issue-register-list-form']) !!}
		                <div class="row">
		                    <div class="col-md-4">
		                        <div class="form-group">
		                            {!! Form::text('issue_code', request()->issue_code, ['class' => 'form-control', 'placeholder' => 'Enter Issue Code']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-4">
		                        <div class="form-group">
		                            {!! Form::text('issue_date', request()->issue_date, ['class' => 'form-control datepicker', 'placeholder' => 'Enter Issue Date']) !!}
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
						        	<th width="9%">Issue Code</th>
						            <th width="12%">Issue Date</th>
						            <th width="14%" class="text-right">Item Qty</th>
						            <th width="12%" class="text-right">Required Qty</th>
						            <th width="12%" class="text-right">Issue Qty</th>
						            <th width="12%" class="text-right">Approve Qty</th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($issueRegisters as $issueRegister)
						        <tr>
						            <td>{{ strtoupper($issueRegister->issue_code) }}</td>
						            <td>{{ Carbon::parse($issueRegister->issue_date)->format('d M, Y') }}</td>
						            <td class="text-right">{{ $issueRegister->item_qty }}</td>
						            <td class="text-right">{{ $issueRegister->req_qty }}</td>
						            <td class="text-right">{{ $issueRegister->issue_qty }}</td>
						            <td class="text-right">{{ $issueRegister->apv_qty }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('issue-registers/' . $issueRegister->issue_code) }}" data-toggle="tooltip" title="View issue register"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('issue-registers/' . $issueRegister->issue_code . '/edit') }}" data-toggle="tooltip" title="Edit issue register"><i class="fa fa-pencil"></i></a>

						                {{-- Approve/Uapprove --}}

										<a href="{{ url('issue-registers/change-approve-status/'.$issueRegister->issue_code) }}" class="btn btn-{{ $issueRegister->apv_qty ? 'warning':'info' }} btn-xs" data-toggle="tooltip" title="{{ $issueRegister->apv_qty ? 'Unapprove':'Approve' }} issue register"><i class="fa fa-{{ $issueRegister->apv_qty ? 'thumbs-o-down':'thumbs-o-up' }} white"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$issueRegister->issue_code}}" data-action="{{ url('issue-registers/delete') }}" data-message="Are you sure, You want to delete this issue register?" class="btn btn-danger btn-xs alert-dialog" data-toggle="tooltip" title="Delete issue register"><i class="fa fa-trash white"></i></a>
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

				@if($issueRegisters->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $issueRegisters->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $issueRegisters->links() !!}
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