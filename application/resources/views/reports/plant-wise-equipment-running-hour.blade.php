@extends('layouts.master')

@section('title') Report of Plantwise Equipment Running Hour @endsection 
@section('page_title') Report of Plantwise Equipment Running Hour @endsection

@section('content')
<div class="card margin-top-20">
	<div class="card-header">
		<h4 class="card-title">Report of Plantwise Equipment Running Hour</h4>
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
			            {!! Form::text('running_year', date('Y'), ['class'=>'form-control datetimepicker', 'placeholder' => 'YYYY', 'id' => 'running_year']) !!}
			        </div>
			    </div>
			    <div class="col-md-3">
			        <div class="form-group">
			            {!! Form::select('running_month', getMonths(), request()->running_month, ['class'=>'form-control chosen-select','id' => 'running_month']) !!}
			        </div>
			    </div>
                <div class="col-md-3">
                    <div class="form-group">
                    	<button class="btn btn-info" data-toggle="tooltip" title="Search"><i class="fa fa-search" aria-hidden="true"></i> Generate Report</button>
                        <a href="{{ url()->current() }}" class="btn btn-default float-right" data-toggle="tooltip" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
    	{!! Form::close() !!}
		
		@if(request()->all())
		<div class="table-responsive">
			@include('reports.plant-wise-equipment-running-hour-table')
		</div>		
		@endif
	</div><!-- end card-body -->

	@if(request()->all())
	<div class="card-footer">
		<a href="{{ url()->current() }}/print?plant_id={{ request()->plant_id }}&running_year={{ request()->running_year }}&running_month={{ request()->running_month }}" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a>
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


