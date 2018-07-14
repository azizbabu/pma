@extends('layouts.master')

@section('title') List of Users @endsection 
@section('page_title') Users @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h3 class="card-title">
						List of Users
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('users/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
					</h3>
					
				</div>

				<div class="card-body">
					
					{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
		                <div class="row">
		                    <div class="col-sm-11 padding-right-0">
		                        <div class="form-group">
		                            {!! Form::text('search_item', request()->current, ['class' => 'form-control', 'placeholder' => 'Search the users by their name,email or phone and hit Enter']) !!}
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
						        	<th width="25%">Email</th>
						            <th width="12%">Phone</th>
						            <th width="18%">Created at</th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($users as $user)
						        <tr>
						            <td>{{ $user->name }}</td>
						            <td>{{ $user->email }}</td>
						            <td>{{ $user->phone }}</td>
						            <td>{{ $user->created_at->format('d M,Y H:i A') }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('users/' . $user->id) }}" title="View User"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('users/' . $user->id . '/edit') }}" title="Edit Task"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$user->id}}" data-action="{{ url('users/delete') }}" data-message="Are you sure, You want to delete this user?" class="btn btn-danger btn-xs alert-dialog" title="Delete User"><i class="fa fa-trash white"></i></a>
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

				@if($users->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $users->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $users->links() !!}
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