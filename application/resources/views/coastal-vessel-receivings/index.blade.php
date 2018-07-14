@extends('layouts.master')

@section('title') List of Coastal Vessel Receivings @endsection 
@section('page_title') Coastal Vessel Receivings @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h4 class="card-title">
						List of Coastal Vessel Receivings
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('coastal-vessel-receivings/create/' . $coastalVesselCarring->id)!!}"><i class="fa fa-plus-circle"></i> Add New</a>
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
		                            {!! Form::text('cvr_number', request()->cvr_number, ['class' => 'form-control', 'placeholder' => 'Enter receiving number']) !!}
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
						        	<th width="10%">CVR NUmber</th>
						        	<th width="12%">CVR Date</th>
						        	<th width="12%">CVR Qty</th>
						        	<th width="12%">Load Qty</th>
						            <th width="16%">Lighter Vessel</th>
						            <th>Plant</th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($coastalVesselReceivings as $coastalVesselReceiving)
						        <tr>
						            <td>{{ strtoupper($coastalVesselReceiving->cvr_number) }}</td>
						            <td>{{ Carbon::parse($coastalVesselReceiving->cvr_date)->format('d M, Y') }}</td>
						            <td>{{ $coastalVesselReceiving->cvr_qty }}</td>
						            <td>{{ $coastalVesselReceiving->load_qty }}</td>
						            <td>{{ $coastalVesselReceiving->lighter_vessel_name ? $coastalVesselReceiving->lighter_vessel_name : 'N/A' }}</td>
						            <td>{{ $coastalVesselReceiving->plant ? $coastalVesselReceiving->plant->name : 'N/A' }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('coastal-vessel-receivings/' . $coastalVesselReceiving->id) }}" data-toggle="tooltip" title="View coastal vessel receiving"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('coastal-vessel-receivings/' . $coastalVesselReceiving->id . '/edit') }}" data-toggle="tooltip" title="Edit coastal vessel receiving"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$coastalVesselReceiving->id}}" data-action="{{ url('coastal-vessel-receivings/delete') }}" data-message="Are you sure, You want to delete this coastal vessel receiving?" class="btn btn-danger btn-xs alert-dialog" data-toggle="tooltip" title="Delete coastal vessel receiving"><i class="fa fa-trash white"></i></a>
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

				@if($coastalVesselReceivings->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $coastalVesselReceivings->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $coastalVesselReceivings->links() !!}
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