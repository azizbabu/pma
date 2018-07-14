@extends('layouts.master')

@section('title') List of Mother Vessel Carrings @endsection 
@section('page_title') Mother Vessel Carrings @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h4 class="card-title">
						List of Mother Vessel Carrings
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('mother-vessel-carrings/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
					</h4>
				</div>

				<div class="card-body">
					
					{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
		                <div class="row">
		                	<div class="col-md-3">
						        <div class="form-group">
						            {!! Form::select('mother_vessel_id', $motherVessels, null, ['class' => 'form-control chosen-select']) !!}
						        </div>
						    </div>
		                    <div class="col-md-6">
		                        <div class="form-group">
		                            {!! Form::text('search_item', request()->current, ['class' => 'form-control', 'placeholder' => 'Enter code or lc number']) !!}
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
						        	<th>Mother Vessel</th>
						        	<th width="7%">Code</th>
						        	<th width="9%">LC No</th>
						            <th width="14%">Invoice Qty(MT)</th>
						            <th width="16%">Received Qty(MT)</th>
						            <th width="16%">Transport Loss(%)</th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($motherVesselCarrings as $motherVesselCarring)
						        <tr>
						            <td>{{ $motherVesselCarring->motherVessel->name }}</td>
						            <td>{{ strtoupper($motherVesselCarring->code) }}</td>
						            <td>{{ strtoupper($motherVesselCarring->lc_number) }}</td>
						            <td>{{ $motherVesselCarring->invoice_quantity }}</td>
						            <td>{{ $motherVesselCarring->received_quantity   }}</td>
						            <td>{{ $motherVesselCarring->transport_loss }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('mother-vessel-carrings/' . $motherVesselCarring->id) }}" title="View mother vessel carring"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('mother-vessel-carrings/' . $motherVesselCarring->id . '/edit') }}" title="Edit mother vessel carring"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$motherVesselCarring->id}}" data-action="{{ url('mother-vessel-carrings/delete') }}" data-message="Are you sure, You want to delete this mother vessel carring?" class="btn btn-danger btn-xs alert-dialog" title="Delete mother vessel carring"><i class="fa fa-trash white"></i></a>
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

				@if($motherVesselCarrings->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $motherVesselCarrings->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $motherVesselCarrings->links() !!}
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