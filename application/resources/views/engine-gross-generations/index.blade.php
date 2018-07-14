@extends('layouts.master')

@section('title') List of Engine Gross Generations @endsection 
@section('page_title') Engine Gross Generations @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h3 class="card-title">
						List of Engine Gross Generations
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('engine-gross-generations/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
					</h3>
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
		                            {!! Form::select('engine_id', $engines, request()->engine_id, ['class' => 'form-control chosen-select']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            {!! Form::text('op_code', request()->op_code, ['class' => 'form-control', 'placeholder' => 'Enter OP Code']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            {!! Form::text('op_date', request()->op_date, ['class' => 'form-control datepicker', 'placeholder' => 'Enter P.O Date']) !!}
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
						        	<th width="18%">Engine</th>
						            <th width="7%" class="text-center">OP Code</th>
						            <th width="12%" class="text-center">OP Date</th>
						            <th width="10%" class="text-center">Start Time</th>
						            <th width="10%" class="text-center">End Time</th>
						            <th width="10%" class="text-center">Diff Time</th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($engineGrossGenerations as $engineGrossGeneration)
						        <tr>
						            <td>{{ $engineGrossGeneration->plant->name }}</td>
						            <td>{{ $engineGrossGeneration->engine->name }}</td>
						            <td class="text-center">{{ strtoupper($engineGrossGeneration->op_code) }}</td>
						            <td class="text-center">{{ Carbon::parse($engineGrossGeneration->op_date)->format('d M, Y') }}</td>
						            <td class="text-center">{{ $engineGrossGeneration->start_time }}</td>
						            <td class="text-center">{{ $engineGrossGeneration->end_time }}</td>
						            <td class="text-center">{{ $engineGrossGeneration->diff_time }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('engine-gross-generations/' . $engineGrossGeneration->id) }}" title="View engine gross generation"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('engine-gross-generations/' . $engineGrossGeneration->id . '/edit') }}" title="Edit engine gross generation"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$engineGrossGeneration->id}}" data-action="{{ url('engine-gross-generations/delete') }}" data-message="Are you sure, You want to delete this engine gross generation?" class="btn btn-danger btn-xs alert-dialog" title="Delete engine gross generation"><i class="fa fa-trash white"></i></a>
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

				@if($engineGrossGenerations->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $engineGrossGenerations->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $engineGrossGenerations->links() !!}
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