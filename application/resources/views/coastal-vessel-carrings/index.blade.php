@extends('layouts.master')

@section('title') List of Coastal Vessel Carrings @endsection 
@section('page_title') Coastal Vessel Carrings @endsection

@section('content')
<div class="card margin-top-20">
	<div class="card-header clearfix">
		<h4 class="card-title">
			List of Coastal Vessel Carrings
			<a class="btn btn-danger btn-xs pull-right" href="{!!url('coastal-vessel-carrings/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
		</h4>
	</div>

	<div class="card-body">
		
		{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
            <div class="row">
            	<div class="col-md-3">
			        <div class="form-group">
			            {!! Form::select('coastal_vessel_id', $coastalVessels, request()->coastal_vessel_id, ['class' => 'form-control chosen-select']) !!}
			        </div>
			    </div>
			    <div class="col-md-3">
			        <div class="form-group">
			            {!! Form::select('tank_id', $tanks, request()->tank_id, ['class' => 'form-control chosen-select']) !!}
			        </div>
			    </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::text('search_item', request()->search_item, ['class' => 'form-control', 'placeholder' => 'Enter carring code']) !!}
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
			        	<th>Coastal Vessel</th>
			        	<th width="7%">Tank No</th>
			        	<th width="7%">Code</th>
			        	<th width="10%">Carring Date</th>
			            <th width="12%">Invoice Qty</th>
			            <th width="10%">Receive Date</th>
			            <th width="12%">Receive Qty</th>
			            <th width="14%">Actions</th>
			        </tr>
			    </thead>
			    <tbody>
			    @forelse($coastalVesselCarrings as $coastalVesselCarring)
			        <tr>
			            <td>{{ $coastalVesselCarring->coastalVessel->name }}</td>
			            <td>{{ strtoupper($coastalVesselCarring->tank->number) }}</td>
			            <td>{{ strtoupper($coastalVesselCarring->code) }}</td>
			            <td>{{ Carbon::parse($coastalVesselCarring->carring_date)->format('d M, Y') }}</td>
			            <td>{{ $coastalVesselCarring->invoice_quantity }}</td>
			            <td>{{ Carbon::parse($coastalVesselCarring->received_date)->format('d M, Y') }}</td>
			            <td>{{ $coastalVesselCarring->received_quantity }}</td>

			            <td class="action-column">
							
							{{-- View --}}
			                <a class="btn btn-xs btn-success" href="{{ URL::to('coastal-vessel-carrings/' . $coastalVesselCarring->id) }}" data-toggle="tooltip" title="View coastal vessel carring"><i class="fa fa-eye"></i></a>

			                {{-- Edit --}}
			                <a class="btn btn-xs btn-default" href="{{ URL::to('coastal-vessel-carrings/' . $coastalVesselCarring->id . '/edit') }}" data-toggle="tooltip" title="Edit coastal vessel carring"><i class="fa fa-pencil"></i></a>
			                
			                {{-- Delete --}}
							<a href="#" data-id="{{$coastalVesselCarring->id}}" data-action="{{ url('coastal-vessel-carrings/delete') }}" data-message="Are you sure, You want to delete this coastal vessel carring?" class="btn btn-danger btn-xs alert-dialog" data-toggle="tooltip" title="Delete coastal vessel carring"><i class="fa fa-trash white"></i></a>

							{{-- Receive --}}
			                <a class="btn btn-xs btn-primary" href="{{ URL::to('coastal-vessel-receivings/list/' . $coastalVesselCarring->id) }}" data-toggle="tooltip" title="View coastal vessel receiving"><i class="fa fa-crosshairs"></i></a>
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

	@if($coastalVesselCarrings->total() > 15)
	<div class="card-footer">
		<div class="row">
			<div class="col-md-4">
				{{ $coastalVesselCarrings->paginationSummary }}
			</div>
			<div class="col-md-8">
				<div class="float-right">
					{!! $coastalVesselCarrings->links() !!}
				</div>
			</div>
		</div>
	</div>
	@endif
</div><!-- end card  -->
		
@endsection