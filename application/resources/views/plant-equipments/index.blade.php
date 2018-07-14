@extends('layouts.master')

@section('title') List of Plant Equipments @endsection 
@section('page_title') Plant Equipments @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h4 class="card-title">
						List of Plant Equipments
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('plant-equipments/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
					</h4>
					
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
		                            {!! Form::text('search_item', request()->search_item, ['class' => 'form-control', 'placeholder' => 'Enter name or code']) !!}
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
						        	<th>Name</th>
						        	<th>Plant</th>
						        	<th width="9%">Code</th>
						            <th width="18%">Created at</th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($plantEquipments as $plantEquipment)
						        <tr>
						        	<td>{{ $plantEquipment->name }}</td>
						            <td>{{ $plantEquipment->plant->name }}</td>
						            <td>{{ strtoupper($plantEquipment->code) }}</td>
						            <td>{{ $plantEquipment->created_at->format('d M,Y H:i A') }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('plant-equipments/' . $plantEquipment->id) }}" data-toggle="tooltip" title="View plant equipment"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('plant-equipments/' . $plantEquipment->id . '/edit') }}" data-toggle="tooltip" title="Edit plant equipment"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$plantEquipment->id}}" data-action="{{ url('plant-equipments/delete') }}" data-message="Are you sure, You want to delete this plant equipment?" class="btn btn-danger btn-xs alert-dialog" data-toggle="tooltip" title="Delete plant equipment"><i class="fa fa-trash white"></i></a>
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

				@if($plantEquipments->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $plantEquipments->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $plantEquipments->links() !!}
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