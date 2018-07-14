@extends('layouts.master')

@section('title') List of Fuel Inventories @endsection 
@section('page_title') Fuel Inventories @endsection

@section('content')
<div class="card margin-top-20">
	<div class="card-header clearfix">
		<h4 class="card-title">
			List of Fuel Inventories
			<a class="btn btn-danger btn-xs pull-right" href="{!!url('fuel-inventories/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
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
			            {!! Form::select('fuel_type_id', $fuelTypes, request()->fuel_type_id, ['class' => 'form-control chosen-select']) !!}
			        </div>
			    </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::text('transaction_date', request()->transaction_date, ['class' => 'form-control datepicker', 'placeholder' => 'Enter Transaction Date']) !!}
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
			        	<th>Plant</th>
			        	<th>Fuel Type</th>
			        	<th width="10%"><span data-toggle="tooltip" title="Transaction Date">Trans. Date</span></th>
			        	<th width="10%"><span data-toggle="tooltip" title="Transaction Code">Trans. Code</span></th>
			            <th width="12%" class="text-right">Available Stock</th>
			            <th width="10%" class="text-right">Consumption</th>
			            <th width="11%">Actions</th>
			        </tr>
			    </thead>
			    <tbody>
			    @forelse($fuelInventories as $fuelInventory)
			        <tr>
			            <td>{{ $fuelInventory->plant->name }}</td>
			            <td>{{ $fuelInventory->fuelType->name }}</td>
			            <td>{{ Carbon::parse($fuelInventory->transaction_date)->format('d M, Y') }}</td>
			            <td>{{ strtoupper($fuelInventory->transaction_code) }}</td>
			            <td class="text-right">{{ number_format($fuelInventory->available_stock, 2) }}</td>
			            <td class="text-right">{{ number_format($fuelInventory->consumption, 2) }}</td>

			            <td class="action-column">
							
							{{-- View --}}
			                <a class="btn btn-xs btn-success" href="{{ URL::to('fuel-inventories/' . $fuelInventory->id) }}" data-toggle="tooltip" title="View fuel inventory"><i class="fa fa-eye"></i></a>

			                {{-- Edit --}}
			                <a class="btn btn-xs btn-default" href="{{ URL::to('fuel-inventories/' . $fuelInventory->id . '/edit') }}" data-toggle="tooltip" title="Edit fuel inventory"><i class="fa fa-pencil"></i></a>
			                
			                {{-- Delete --}}
							<a href="#" data-id="{{$fuelInventory->id}}" data-action="{{ url('fuel-inventories/delete') }}" data-message="Are you sure, You want to delete this fuel inventory?" class="btn btn-danger btn-xs alert-dialog" data-toggle="tooltip" title="Delete fuel inventory"><i class="fa fa-trash white"></i></a>
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

	@if($fuelInventories->total() > 15)
	<div class="card-footer">
		<div class="row">
			<div class="col-md-4">
				{{ $fuelInventorys->paginationSummary }}
			</div>
			<div class="col-md-8">
				<div class="float-right">
					{!! $fuelInventorys->links() !!}
				</div>
			</div>
		</div>
	</div>
	@endif
</div><!-- end card  -->
		
@endsection