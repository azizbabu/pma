@extends('layouts.master')

@section('title') Report of Overall Equipment Running Hour @endsection 
@section('page_title') Report of Overall Equipment Running Hour @endsection

@section('content')
<div class="card margin-top-20">
	<div class="card-header">
		<h4 class="card-title">Report of Overall Equipment Running Hour</h4>
	</div>

	<div class="card-body">
		
		{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
            <div class="row">
			    <div class="col-md-4">
			        <div class="form-group">
			            {!! Form::text('running_year', date('Y'), ['class'=>'form-control datetimepicker', 'placeholder' => 'YYYY', 'id' => 'running_year']) !!}
			        </div>
			    </div>
			    <div class="col-md-4">
			        <div class="form-group">
			            {!! Form::select('running_month', getMonths(), request()->running_month, ['class'=>'form-control chosen-select','id' => 'running_month']) !!}
			        </div>
			    </div>
                <div class="col-md-4">
                    <div class="form-group">
                    	<button class="btn btn-info" data-toggle="tooltip" title="Search"><i class="fa fa-search" aria-hidden="true"></i> Generate Report</button>
                        <a href="{{ url()->current() }}" class="btn btn-default float-right" data-toggle="tooltip" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
    	{!! Form::close() !!}
		
		@if(request()->all())
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-sm">
				<thead>
					<tr>
						<th class="text-center align-middle" colspan="2">Name of the plant</th>
						@foreach($plants as $plant)
							<th class="text-center align-middle" colspan="2">{{ getShortNames($plant->name) }}</th>
						@endforeach
					</tr>
					<tr>
						<th class="text-center align-middle" rowspan="2" width="7%">SL No</th>
						<th class="align-middle" rowspan="2">Equipment Name</th>
						@foreach($plants as $plant)
							<th class="text-center align-middle">RH of this month</th>
							<th class="text-center align-middle">Month end</th>
						@endforeach
					</tr>
				</thead>
				<tbody>
					@php $i=1 @endphp
					@foreach($equipments as $equipment)
					<tr>
						<th class="text-center align-middle">{{ $i++ }}</th>
						<td class="align-middle">{{ $equipment->name }}</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ $plant_info[$plant->id][$equipment->id]['diff_value'] }}</td>
							<td class="text-center align-middle">{{ $plant_info[$plant->id][$equipment->id]['end_value'] }}</td>
						@endforeach
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>		
		@endif
	</div><!-- end card-body -->

	@if(request()->all())
	<div class="card-footer">
		<a href="{{ url()->current() }}/print?plant_id={{ request()->plant_id }}&operation_date={{ request()->operation_date }}" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a>
	</div>
	@endif
</div><!-- end card  -->
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