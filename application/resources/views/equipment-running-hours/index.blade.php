@extends('layouts.master')

@section('title') List of Equipment Running Hour @endsection 
@section('page_title') Equipment Running Hours @endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card margin-top-20">
				<div class="card-header clearfix">
					<h3 class="card-title">
						List of Equipment Running Hour
						<a class="btn btn-danger btn-xs pull-right" href="{!!url('equipment-running-hours/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
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
		                            {!! Form::select('plant_equipment_id', $plantEquipments, request()->plant_equipment_id, ['class' => 'form-control chosen-select']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            {!! Form::text('running_year', request()->running_year, ['class'=>'form-control datetimepicker','id' => 'running_year', 'placeholder' => 'Enter Year']) !!}
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                    	<div class="form-group">
	                            	{!! Form::select('running_month', getMonths(), request()->running_month, ['class' => 'form-control chosen-select']) !!}
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
						        	<th width="16%">Plant</th>
						        	<th width="16%">Equipment</th>
						        	<th width="10%">Running Year</th>
						            <th width="10%">Runing Month</th>
						            <th width="10%">Start Value</th>
						            <th width="10%">End Value</th>
						            <th width="10%">Diff Value</th>
						            <th width="12%">Actions</th>
						        </tr>
						    </thead>
						    <tbody>
						    @forelse($equipmentRunningHours as $equipmentRunningHour)
						        <tr>
						        	<td>{{ $equipmentRunningHour->plant->name }}</td>
						            <td>{{ $equipmentRunningHour->plantEquipment->name }}</td>
						            <td>{{ $equipmentRunningHour->running_year }}</td>
						            <td>{{ getMonths($equipmentRunningHour->running_month) }}</td>
						            <td>{{ $equipmentRunningHour->start_value }}</td>
						            <td>{{ $equipmentRunningHour->end_value }}</td>
						            <td>{{ $equipmentRunningHour->diff_value }}</td>

						            <td class="action-column">
										
										{{-- View --}}
						                <a class="btn btn-xs btn-success" href="{{ URL::to('equipment-running-hours/' . $equipmentRunningHour->id) }}" title="View equipment running hour"><i class="fa fa-eye"></i></a>

						                {{-- Edit --}}
						                <a class="btn btn-xs btn-default" href="{{ URL::to('equipment-running-hours/' . $equipmentRunningHour->id . '/edit') }}" title="Edit equipment running hour"><i class="fa fa-pencil"></i></a>
						                
						                {{-- Delete --}}
										<a href="#" data-id="{{$equipmentRunningHour->id}}" data-action="{{ url('equipment-running-hours/delete') }}" data-message="Are you sure, You want to delete this equipment running hour?" class="btn btn-danger btn-xs alert-dialog" title="Delete equipment running hour"><i class="fa fa-trash white"></i></a>
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

				@if($equipmentRunningHours->total() > 15)
				<div class="card-footer">
					<div class="row">
						<div class="col-md-4">
							{{ $equipmentRunningHours->paginationSummary }}
						</div>
						<div class="col-md-8">
							<div class="float-right">
								{!! $equipmentRunningHours->links() !!}
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

@section('custom-style')
{!! Html::style($assets . '/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') !!}
@endsection

@section('custom-script')
{!! Html::script($assets . '/plugins/bootstrap-datetimepicker/js/moment-with-locales.js') !!}
{!! Html::script($assets . '/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') !!}
<script>
(function() {
    $('.datetimepicker').datetimepicker({
        viewMode: 'years',
        format: 'YYYY'
    });
})();
</script>

@endsection