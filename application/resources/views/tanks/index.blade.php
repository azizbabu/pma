@extends('layouts.master')

@section('title') List of Tanks @endsection 
@section('page_title') Tanks @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h4 class="card-title">
						List of Tanks
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('tanks/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
					</h4>
					
				</div>

				<div class="card-body">
					
					{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
		                <div class="row">
		                	<div class="col-md-4">
		                        <div class="form-group">
		                            {!! Form::select('terminal_id', $terminals, request()->terminal_id, ['class' => 'form-control chosen-select']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-4">
		                        <div class="form-group">
		                            {!! Form::text('number', request()->number, ['class' => 'form-control', 'placeholder' => 'Enter Tank Number']) !!}
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
						        	<th>Terminal</th>
						        	<th width="7%">Number</th>
						            <th width="12%">Capacity(MT)</th>
						            <th width="18%">Created at</th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($tanks as $tank)
						        <tr>
						            <td>{{ $tank->terminal->name }}</td>
						            <td>{{ strtoupper($tank->number) }}</td>
						            <td>{{ $tank->capacity }}</td>
						            <td>{{ $tank->created_at->format('d M,Y H:i A') }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('tanks/' . $tank->id) }}" title="View tank"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('tanks/' . $tank->id . '/edit') }}" title="Edit tank"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$tank->id}}" data-action="{{ url('tanks/delete') }}" data-message="Are you sure, You want to delete this tank?" class="btn btn-danger btn-xs alert-dialog" title="Delete tank"><i class="fa fa-trash white"></i></a>
						            </td>
						        </tr>

						    @empty
						    	<tr>
						        	<td colspan="5" align="center">No Record Found!</td>
						        </tr>
						    @endforelse
						    </tbody>
						</table>
					</div>
				</div><!-- end card-body -->

				@if($tanks->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $tanks->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $tanks->links() !!}
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