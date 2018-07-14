@extends('layouts.master')

@section('title') List of Engine Generations @endsection 
@section('page_title') Engine Generations @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h3 class="card-title">
						List of Engine Generations
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('engine-generations/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
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
		                            {!! Form::text('gen_code', request()->gen_code, ['class' => 'form-control', 'placeholder' => 'Enter Gen Code']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            {!! Form::text('gen_date', request()->gen_date, ['class' => 'form-control datepicker', 'placeholder' => 'Enter Gen Date']) !!}
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
						            <th width="7%" class="text-center">Gen Code</th>
						            <th width="12%" class="text-center">Gen Date</th>
						            <th width="10%" class="text-center">Start</th>
						            <th width="10%" class="text-center">End</th>
						            <th width="10%" class="text-center">Total</th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($engineGenerations as $engineGeneration)
						        <tr>
						            <td>{{ $engineGeneration->plant->name }}</td>
						            <td>{{ $engineGeneration->engine->name }}</td>
						            <td class="text-center">{{ strtoupper($engineGeneration->gen_code) }}</td>
						            <td class="text-center">{{ Carbon::parse($engineGeneration->gen_date)->format('d M, Y') }}</td>
						            <td class="text-center">{{ $engineGeneration->start }}</td>
						            <td class="text-center">{{ $engineGeneration->end }}</td>
						            <td class="text-center">{{ $engineGeneration->total }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('engine-generations/' . $engineGeneration->id) }}" data-toggle="tooltip" title="View engine generation"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('engine-generations/' . $engineGeneration->id . '/edit') }}" data-toggle="tooltip" title="Edit engine generation"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$engineGeneration->id}}" data-action="{{ url('engine-generations/delete') }}" data-message="Are you sure, You want to delete this engine generation?" class="btn btn-danger btn-xs alert-dialog" data-toggle="tooltip" title="Delete engine generation"><i class="fa fa-trash white"></i></a>
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

				@if($engineGenerations->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $engineGenerations->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $engineGenerations->links() !!}
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