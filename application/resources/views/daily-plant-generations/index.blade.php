@extends('layouts.master')

@section('title') List of Daily Plant Generations @endsection 
@section('page_title') Daily Plant Generations @endsection

@section('content')

<div class="card margin-top-20">
	<div class="card-header clearfix">
		<h3 class="card-title">
			List of Daily Plant Generations
			<a class="btn btn-danger btn-xs pull-right" href="{!!url('daily-plant-generations/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
		</h3>
	</div>

	<div class="card-body">
		
		{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					{!! Form::select('plant_id', $plants, request()->plant_id, ['class' => 'form-control chosen-select']) !!}
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					{!! Form::text('operation_date', request()->operation_date, ['class' => 'form-control datepicker', 'placeholder' => 'Enter Operation Date']) !!}
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
						<th>Plant</th>
						<th width="12%" class="text-center">Operation Date</th>
						<th width="12%" class="text-center">Plant Load Factor(%)</th>
						<th width="12%" class="text-center">Plant Fuel Consumption</th>
						<th class="text-center">Total HFO Stock</th>
						<th class="text-center">Reference LHV</th>
						<th width="12%">Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse($dailyPlantGenerations as $dailyPlantGeneration)
					<tr>
						<td>{{ $dailyPlantGeneration->plant->name }}</td>
						<td>{{ Carbon::parse($dailyPlantGeneration->operation_date)->format('d M, Y') }}</td>
						<td class="text-center">{{ $dailyPlantGeneration->plant_load_factor }}</td>
						<td class="text-center">{{ number_format($dailyPlantGeneration-> 	plant_fuel_consumption, 2) }}</td>
						<td class="text-center">{{ number_format($dailyPlantGeneration-> 	total_hfo_stock, 2) }}</td>
						<td class="text-center">{{ number_format($dailyPlantGeneration-> 	reference_lhv, 2) }}</td>

						<td class="action-column">
							
							{{-- View --}}
							<a class="btn btn-xs btn-success" href="{{ URL::to('daily-plant-generations/' . $dailyPlantGeneration->id) }}" title="View energy gross generation"><i class="fa fa-eye"></i></a>

							{{-- Edit --}}
							<a class="btn btn-xs btn-default" href="{{ URL::to('daily-plant-generations/' . $dailyPlantGeneration->id . '/edit') }}" title="Edit energy gross generation"><i class="fa fa-pencil"></i></a>
							
							{{-- Delete --}}
							<a href="#" data-id="{{$dailyPlantGeneration->id}}" data-action="{{ url('daily-plant-generations/delete') }}" data-message="Are you sure, You want to delete this energy gross generation?" class="btn btn-danger btn-xs alert-dialog" title="Delete energy gross generation"><i class="fa fa-trash white"></i></a>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="8" align="center">No Record Found!</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div><!-- end card-body -->

	@if($dailyPlantGenerations->total() > 15)
	<div class="card-footer">
		<div class="row">
			<div class="col-md-4">
				{{ $dailyPlantGenerations->paginationSummary }}
			</div>
			<div class="col-md-8">
				<div class="float-right">
					{!! $dailyPlantGenerations->links() !!}
				</div>
			</div>
		</div>
	</div>
	@endif
</div><!-- end card  -->

@endsection