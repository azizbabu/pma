@extends('layouts.master')

@section('title') List of Meters @endsection 
@section('page_title') Meters @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h4 class="card-title">
						List of Meters
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('meters/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
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
		                    <div class="col-md-6">
		                        <div class="form-group">
		                            {!! Form::text('search_item', request()->search_item, ['class' => 'form-control', 'placeholder' => 'Enter name or number']) !!}
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
						        	<th>Name</th>
						        	<th>Plant</th>
						        	<th width="7%">Number</th>
						            <th width="12%">Capacity</th>
						            <th width="7%">Unit</th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($meters as $meter)
						        <tr>
						            <td>{{ $meter->name }}</td>
						            <td>{{ $meter->plant->name }}</td>
						            <td>{{ strtoupper($meter->number) }}</td>
						            <td>{{ $meter->capacity }}</td>
						            <td>{{ $meter->unit }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('meters/' . $meter->id) }}" title="View meter"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('meters/' . $meter->id . '/edit') }}" title="Edit meter"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$meter->id}}" data-action="{{ url('meters/delete') }}" data-message="Are you sure, You want to delete this meter?" class="btn btn-danger btn-xs alert-dialog" title="Delete meter"><i class="fa fa-trash white"></i></a>
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

				@if($meters->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $meters->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $meters->links() !!}
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