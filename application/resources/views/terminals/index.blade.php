@extends('layouts.master')

@section('title') List of Terminals @endsection 
@section('page_title') Terminals @endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card margin-top-20">
			<div class="card-header clearfix">
				<h4 class="card-title">
					List of Terminals
					<a class="btn btn-danger btn-xs pull-right" href="{!!url('terminals/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
				</h4>
				
			</div>

			<div class="card-body">
				
				{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
	                <div class="row">
	                    <div class="col-sm-11 padding-right-0">
	                        <div class="form-group">
	                            {!! Form::text('search_item', request()->current, ['class' => 'form-control', 'placeholder' => 'Search the terminals by its name, or code and hit Enter']) !!}
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
					            <th width="12%">Capacity(MT)</th>
					            <th width="18%">Created at</th>
					            <th width="12%">Actions</th>
					        </tr>
					    </thead>
					    <tbody>
					    @forelse($terminals as $terminal)
					        <tr>
					            <td>{{ $terminal->name }}</td>
					            <td>{{ strtoupper($terminal->code) }}</td>
					            <td>{{ $terminal->capacity }}</td>
					            <td>{{ $terminal->created_at->format('d M,Y H:i A') }}</td>

					            <td class="action-column">
									
									{{-- View --}}
					                <a class="btn btn-xs btn-success" href="{{ URL::to('terminals/' . $terminal->id) }}" title="View terminal"><i class="fa fa-eye"></i></a>

					                {{-- Edit --}}
					                <a class="btn btn-xs btn-default" href="{{ URL::to('terminals/' . $terminal->id . '/edit') }}" title="Edit terminal"><i class="fa fa-pencil"></i></a>
					                
					                {{-- Delete --}}
									<a href="#" data-id="{{$terminal->id}}" data-action="{{ url('terminals/delete') }}" data-message="Are you sure, You want to delete this terminal?" class="btn btn-danger btn-xs alert-dialog" title="Delete terminal"><i class="fa fa-trash white"></i></a>
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

			@if($terminals->total() > 15)
			<div class="card-footer">
				<div class="row">
					<div class="col-md-4">
						{{ $terminals->paginationSummary }}
					</div>
					<div class="col-md-8">
						<div class="float-right">
							{!! $terminals->links() !!}
						</div>
					</div>
				</div>
			</div>
			@endif
		</div><!-- end card  -->
	</div>
</div>
@endsection