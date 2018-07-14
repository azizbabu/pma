@extends('layouts.master')

@section('title') List of Items @endsection 
@section('page_title') Items @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h4 class="card-title">
						List of Items
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('items/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
					</h4>
				</div>

				<div class="card-body">
					
					{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
		                <div class="row">
		                	<div class="col-md-3">
						        <div class="form-group">
						            {!! Form::select('item_group_id', $itemGroups, null, ['class' => 'form-control chosen-select']) !!}
						        </div>
						    </div>
						    <div class="col-md-3">
						        <div class="form-group">
						            {!! Form::select('plant_id', $plants, null, ['class' => 'form-control chosen-select']) !!}
						        </div>
						    </div>
		                    <div class="col-md-3">
		                        <div class="form-group">
		                            {!! Form::text('search_item', request()->current, ['class' => 'form-control', 'placeholder' => 'Enter item name or code']) !!}
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
						        	<th width="18%">Item Group</th>
						        	<th width="18%">Plant</th>
						        	<th>Name</th>
						        	<th width="7%">Code</th>
						            <th width="9%"><span data-toggle="tooltip" title="Source Type">Src Type</span></th>
						            <th width="9%"><span data-toggle="tooltip" title="Stock Type">Stk Type</span></th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($items as $item)
						        <tr>
						            <td>{{ $item->itemGroup->name }}</td>
						            <td>{{ $item->plant->name }}</td>
						            <td>{{ $item->name }}</td>
						            <td>{{ strtoupper($item->code) }}</td>
						            <td>{{ ucfirst($item->source_type) }}</td>
						            <td>{{ ucfirst($item->stock_type) }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('items/' . $item->id) }}" title="View item"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('items/' . $item->id . '/edit') }}" title="Edit item"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$item->id}}" data-action="{{ url('items/delete') }}" data-message="Are you sure, You want to delete this item?" class="btn btn-danger btn-xs alert-dialog" title="Delete item"><i class="fa fa-trash white"></i></a>
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

				@if($items->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $items->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $items->links() !!}
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