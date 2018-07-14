@extends('layouts.master')

@section('title') List of Item Types @endsection 
@section('page_title') Item Types @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h4 class="card-title">
						List of Item Types
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('item-groups/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
					</h4>
					
				</div>

				<div class="card-body">
					
					{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
		                <div class="row">
		                    <div class="col-sm-11 padding-right-0">
		                        <div class="form-group">
		                            {!! Form::text('search_item', request()->current, ['class' => 'form-control', 'placeholder' => 'Search the item types by its name, or code and hit Enter']) !!}
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
						            <th width="18%">Created at</th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($itemGroups as $itemGroup)
						        <tr>
						            <td>{{ $itemGroup->name }}</td>
						            <td>{{ strtoupper($itemGroup->code) }}</td>
						            <td>{{ $itemGroup->created_at->format('d M,Y H:i A') }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('item-groups/' . $itemGroup->id) }}" title="View item type"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('item-groups/' . $itemGroup->id . '/edit') }}" title="Edit item type"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$itemGroup->id}}" data-action="{{ url('item-groups/delete') }}" data-message="Are you sure, You want to delete this item type?" class="btn btn-danger btn-xs alert-dialog" title="Delete item type"><i class="fa fa-trash white"></i></a>
						            </td>
						        </tr>

						    @empty
						    	<tr>
						        	<td colspan="4" align="center">No Record Found!</td>
						        </tr>
						    @endforelse
						    </tbody>
						</table>
					</div>
				</div><!-- end card-body -->

				@if($itemGroups->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $itemGroups->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $itemGroups->links() !!}
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