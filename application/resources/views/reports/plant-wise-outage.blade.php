@extends('layouts.master')

@section('title') Report of Plantwise Outage @endsection 
@section('page_title') Report of Plantwise Outage @endsection

@section('content')
<div class="card margin-top-20">
	<div class="card-header">
		<h4 class="card-title">Report of Plantwise Outage</h4>
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
				        <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
						    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						    <span></span> <b class="caret"></b>
						</div>
						{!! Form::hidden('date_range', Request::input('date_range')) !!}
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
		
		@if($plant_info)
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-sm">
				<thead>
					<tr>
						<th class="text-center align-middle"></th>
						<th class="text-center align-middle"></th>
						<th class="text-center align-middle">This month </th>
						<th class="text-center align-middle">Last Month</th>
						<th class="text-center align-middle">YTD</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th class="align-middle">Date of COD</th>
						<td class="text-center align-middle"></td>
						<td class="text-center align-middle"></td>
						<td class="text-center align-middle"></td>
						<td class="text-center align-middle"></td>
					</tr>
					<tr>
						<th class="align-middle">Dependable Capacity as per PPA</th>
						<td class="text-center align-middle">MW</td>
						<td class="text-center align-middle">{{ $plant_info['dependable_capacity']['this_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['dependable_capacity']['last_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['dependable_capacity']['ytd'] }}</td>
					</tr>
					<tr>
						<th class="align-middle">Net Generation</th>
						<td class="text-center align-middle">MWH</td>
						<td class="text-center align-middle">{{ number_format($plant_info['net_generation']['this_month'], 3) }}</td>
						<td class="text-center align-middle">{{ number_format($plant_info['net_generation']['last_month'], 3) }}</td>
						<td class="text-center align-middle">{{ number_format($plant_info['net_generation']['ytd'], 3) }}</td>
					</tr>
					<tr>
						<th class="align-middle">Running hr</th>
						<td class="text-center align-middle">hr</td>
						<td class="text-center align-middle">{{ $plant_info['engine-running']['this_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['engine-running']['last_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['engine-running']['ytd'] }}</td>
					</tr>
					<tr>
						<th class="align-middle">Maintenance Outage</th>
						<td class="text-center align-middle">hr</td>
						<td class="text-center align-middle">{{ $plant_info['maintenance-outage']['this_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['maintenance-outage']['last_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['maintenance-outage']['ytd'] }}</td>
					</tr>
					<tr>
						<th class="align-middle">Scheduled Outage</th>
						<td class="text-center align-middle">hr</td>
						<td class="text-center align-middle">{{ $plant_info['schedule-outage']['this_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['schedule-outage']['last_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['schedule-outage']['ytd'] }}</td>
					</tr>
					<tr>
						<th class="align-middle">Force Outage for Grid</th>
						<td class="text-center align-middle">hr</td>
						<td class="text-center align-middle">{{ $plant_info['force-outage']['this_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['force-outage']['last_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['force-outage']['ytd'] }}</td>
					</tr>
					<tr>
						<th class="align-middle">This month outage including Grid</th>
						<td class="text-center align-middle">hr</td>
						<td class="text-center align-middle">{{ $plant_info['this_month_outage_including_grid']['this_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['this_month_outage_including_grid']['last_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['this_month_outage_including_grid']['ytd'] }}</td>
					</tr>
					<tr>
						<th class="align-middle">This month  outage  Excluding  Grid</th>
						<td class="text-center align-middle">hr</td>
						<td class="text-center align-middle">{{ $plant_info['this_month_outage_excluding_grid']['this_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['this_month_outage_excluding_grid']['last_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['this_month_outage_excluding_grid']['ytd'] }}</td>
					</tr>
					<tr>
						<th class="align-middle">Reserve Shut down in hr</th>
						<td class="text-center align-middle">hr</td>
						<td class="text-center align-middle">{{ $plant_info['reverse_shut_down']['this_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['reverse_shut_down']['last_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['reverse_shut_down']['ytd'] }}</td>
					</tr>
					<tr>
						<th class="align-middle">Total Permissible Outage</th>
						<td class="text-center align-middle">hr</td>
						<td class="text-center align-middle">{{ $plant_info['total_permissible_outage']['this_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['total_permissible_outage']['last_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['total_permissible_outage']['ytd'] }}</td>
					</tr>
					<tr>
						<th class="align-middle">YTD Outage (Including Grid)</th>
						<td class="text-center align-middle">hr</td>
						<td class="text-center align-middle">{{ $plant_info['ytd_outage_including_grid']['this_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['ytd_outage_including_grid']['last_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['ytd_outage_including_grid']['ytd'] }}</td>
					</tr>
					<tr>
						<th class="align-middle">YTD  Outage (Excluding Grid)</th>
						<td class="text-center align-middle">hr</td>
						<td class="text-center align-middle">{{ $plant_info['ytd_outage_excluding_grid']['this_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['ytd_outage_excluding_grid']['last_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['ytd_outage_excluding_grid']['ytd'] }}</td>
					</tr>
					<tr>
						<th class="align-middle">Remaining Permissible Outage for this year  (including Grid)</th>
						<td class="text-center align-middle">hr</td>
						<td class="text-center align-middle">{{ $plant_info['remaining_permissible_outage_including_grid']['this_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['remaining_permissible_outage_including_grid']['last_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['remaining_permissible_outage_including_grid']['ytd'] }}</td>
					</tr>
					<tr>
						<th class="align-middle">Remaining Permissible Outage for this year (Excluding  Grid)</th>
						<td class="text-center align-middle">hr</td>
						<td class="text-center align-middle">{{ $plant_info['remaining_permissible_outage_excluding_grid']['this_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['remaining_permissible_outage_excluding_grid']['last_month'] }}</td>
						<td class="text-center align-middle">{{ $plant_info['remaining_permissible_outage_excluding_grid']['ytd'] }}</td>
					</tr>
					<tr>
						<th class="align-middle">Remaining Permissible Outage for this year  (including Grid)</th>
						<td class="text-center align-middle">MWh</td>
						<td class="text-center align-middle">{{ number_format($plant_info['remaining_permissible_outage_including_grid_mwh']['this_month'], 3) }}</td>
						<td class="text-center align-middle">{{ number_format($plant_info['remaining_permissible_outage_including_grid_mwh']['last_month'], 3) }}</td>
						<td class="text-center align-middle">{{ number_format($plant_info['remaining_permissible_outage_including_grid_mwh']['ytd'], 3) }}</td>
					</tr>
					<tr>
						<th class="align-middle">Remaining Permissible Outage for this year (Excluding  Grid)</th>
						<td class="text-center align-middle">MWh</td>
						<td class="text-center align-middle">{{ number_format($plant_info['remaining_permissible_outage_excluding_grid_mwh']['this_month'], 3) }}</td>
						<td class="text-center align-middle">{{ number_format($plant_info['remaining_permissible_outage_excluding_grid_mwh']['last_month'], 3) }}</td>
						<td class="text-center align-middle">{{ number_format($plant_info['remaining_permissible_outage_excluding_grid_mwh']['ytd'], 3) }}</td>
					</tr>
				</tbody>
			</table>
		</div>		
		@endif
	</div><!-- end card-body -->

	@if(request()->all())
	<div class="card-footer">
		<a href="{{ url()->current() }}/print?plant_id={{ request()->plant_id }}&date_range={{ request()->date_range }}" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a>
	</div>
	@endif
</div><!-- end card  -->
@endsection

@section('custom-style')
{{-- Daterange Picker --}}
{!! Html::style($assets . '/plugins/daterangepicker/daterangepicker-bs3.css') !!}
@endsection

@section('custom-script')
{{-- Date rangepicker --}}
{!! Html::script($assets . '/plugins/daterangepicker/moment.min.js') !!}
{!! Html::script($assets . '/plugins/daterangepicker/daterangepicker.js') !!}

<script>
(function() {

	@if(!empty($from_date) && !empty($to_date))
	var start = moment('{{ $from_date }}');
	var end = moment('{{ $to_date }}');
	@else
	@if($date_range = request()->old('date_range'))
		@php 
		$date_range_arr = explode(' - ', $date_range);
		@endphp
		var start = moment('{{ $date_range_arr[0] }}');
		var end = moment('{{ $date_range_arr[1] }}');
	@else
	var start = moment().subtract(29, 'days');
    var end = moment();
    @endif
    @endif

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('input[name=date_range]').val(start.format('YYYY-MM-DD')+ ' - ' + end.format('YYYY-MM-DD'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

	cb(start, end);
})();
</script>
@endsection