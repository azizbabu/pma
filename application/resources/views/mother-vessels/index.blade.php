@extends('layouts.master')

@section('title') List of Mother Vessels @endsection 
@section('page_title') Mother Vessels @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h4 class="card-title">
						List of Mother Vessels
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('mother-vessels/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
					</h4>
					
				</div>

				<div class="card-body">
					
					{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
		                <div class="row">
		                    <div class="col-sm-11 padding-right-0">
		                        <div class="form-group">
		                            {!! Form::text('search_item', request()->current, ['class' => 'form-control', 'placeholder' => 'Search the mother vessels by its name, or code and hit Enter']) !!}
		                        </div>
		                    </div>
		                    <div class="col-sm-1">
		                        <div class="form-group">
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
						        	<th width="7%">Code</th>
						        	<th width="20%">Contact Person Name</th>
						            <th width="14%">Phone</th>
						            <th width="20%">Email</th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($mother_vessels as $mother_vessel)
						        <tr>
						            <td>{{ $mother_vessel->name }}</td>
						            <td>{{ strtoupper($mother_vessel->code) }}</td>
						            <td>{{ $mother_vessel->contact_person_name }}</td>
						            <td>{{ $mother_vessel->contact_person_phone ? $mother_vessel->contact_person_phone : 'N/A'  }}</td>
						            <td>{{ $mother_vessel->contact_person_email ? $mother_vessel->contact_person_email : 'N/A' }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('mother-vessels/' . $mother_vessel->id) }}" title="View mother_vessel"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('mother-vessels/' . $mother_vessel->id . '/edit') }}" title="Edit mother_vessel"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$mother_vessel->id}}" data-action="{{ url('mother-vessels/delete') }}" data-message="Are you sure, You want to delete this mother_vessel?" class="btn btn-danger btn-xs alert-dialog" title="Delete mother_vessel"><i class="fa fa-trash white"></i></a>
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

				@if($mother_vessels->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $mother_vessels->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $mother_vessels->links() !!}
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