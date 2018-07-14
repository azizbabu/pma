@extends('layouts.master')

@section('title') Report of Overall Operation @endsection 
@section('page_title') Report of Overall Operation @endsection

@section('content')
<div class="card margin-top-20">
	<div class="card-header">
		<h4 class="card-title">Report of Overall Operation</h4>
	</div>

	<div class="card-body">
		
		{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
            <div class="row">
			    <div class="col-md-6">
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
		
		@if(request()->all())
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-sm">
				<thead>
					<tr>
						<th class="text-center align-middle">Monthly Operation Information</th>
						<th class="text-center align-middle"></th>
						@foreach($plants as $plant)
							<th class="text-center align-middle" colspan="2">{{ getShortNames($plant->name) }}</th>
						@endforeach
					</tr>
					<tr>
						<th class="text-center align-middle">Generation :</th>
						<th class="text-center align-middle"></th>
						@foreach($plants as $plant)
							<th class="text-center align-middle">This month</th>
							<th class="text-center align-middle">YTD</th>
						@endforeach
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Gross Generation</th>
						<td class="text-center">MWh</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['gross_generation']['this_month'], 3) }}</td>
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['gross_generation']['ytd'], 3) }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Net Generation</th>
						<td class="text-center">MWh</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['net_generation']['this_month'], 3) }}</td>
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['net_generation']['ytd'], 3) }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Energy Import</th>
						<td class="text-center">MWh</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['energy_import']['this_month'], 3) }}</td>
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['energy_import']['ytd'], 3) }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Net Export</th>
						<td class="text-center">MWh</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['net_export']['this_month'], 3) }}</td>
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['net_export']['ytd'], 3) }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Station Load</th>
						<td class="text-center">MWh</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['station_load_mwh']['this_month'], 3) }}</td>
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['station_load_mwh']['ytd'], 3) }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Station Load</th>
						<td class="text-center">%</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['station_load_percentage']['this_month'] ? number_format($plant_info[$plant->id]['station_load_percentage']['this_month'], 2) : 'N/A' }}</td>
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['station_load_percentage']['ytd'] ? number_format($plant_info[$plant->id]['station_load_percentage']['ytd'], 2) : 'N/A' }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Plant Load Factor (PLF)</th>
						<td class="text-center">%</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['plf']['this_month'] ? number_format($plant_info[$plant->id]['plf']['this_month'], 2) : 'N/A' }}</td>
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['plf']['ytd'] ? number_format($plant_info[$plant->id]['plf']['ytd'], 2) : 'N/A' }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Plant Availability & Reliability :</th>
						<td class="text-center"></td>
						@foreach($plants as $plant)
							<td class="text-center align-middle"></td>
							<td class="text-center align-middle"></td>
						@endforeach
					</tr>
					<tr>
						<th>Start </th>
						<td class="text-center">Nos</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle"></td>
							<td class="text-center align-middle"></td>
						@endforeach
					</tr>
					<tr>
						<th>Running Hours </th>
						<td class="text-center">Hrs</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['engine-running']['this_month'] }}</td>
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['engine-running']['ytd'] }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Schedule Outage </th>
						<td class="text-center">Hrs</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['schedule-outage']['this_month'] }}</td>
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['schedule-outage']['ytd'] }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Maintenance Outage </th>
						<td class="text-center">Hrs</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['maintenance-outage']['this_month'] }}</td>
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['maintenance-outage']['ytd'] }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Reserve Shutdown </th>
						<td class="text-center">Hrs</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['reverse_shut_down']['this_month'] }}</td>
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['reverse_shut_down']['ytd'] }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Force Outage (Grid) </th>
						<td class="text-center">Hrs</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['force-outage']['this_month'] }}</td>
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['force-outage']['ytd'] }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Plant Availability </th>
						<td class="text-center">%</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['plant_availability']['this_month'], 2) }}</td>
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['plant_availability']['ytd'], 2) }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Plant Reliability </th>
						<td class="text-center">%</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['plant_reliability']['this_month'], 2) }}</td>
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['plant_reliability']['ytd'], 2) }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Plant Utilization </th>
						<td class="text-center">%</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['plant_utilization']['this_month'], 2) }}</td>
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['plant_utilization']['ytd'], 2) }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Fuel Consumption & Heat Rate :</th>
						<td class="text-center"></td>
						@foreach($plants as $plant)
							<td class="text-center align-middle"></td>
							<td class="text-center align-middle"></td>
						@endforeach
					</tr>
					<tr>
						<th>Total Fuel Consumption ( Flowmeter ) </th>
						<td class="text-center">MT</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['total_fuel_consumption_flowmeter']['this_month'], 3) }}</td>
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['total_fuel_consumption_flowmeter']['ytd'], 3) }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Total Fuel Consumption(Tank Sounding) </th>
						<td class="text-center">MT</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['total_fuel_consumption_tank']['this_month'], 3) }}</td>
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['total_fuel_consumption_tank']['ytd'], 3) }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Auxiliary Boiler HFO Consmp. (Assumption) </th>
						<td class="text-center">MT</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['aux_boiler_hfo_consumption']['this_month'], 3) }}</td>
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['aux_boiler_hfo_consumption']['ytd'], 3) }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Sludge Production </th>
						<td class="text-center">%</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ is_numeric($plant_info[$plant->id]['sludge_production']['this_month']) ? number_format($plant_info[$plant->id]['sludge_production']['this_month'], 2) : 'N/A' }}</td>
							<td class="text-center align-middle">{{ is_numeric($plant_info[$plant->id]['sludge_production']['ytd']) ? number_format($plant_info[$plant->id]['sludge_production']['ytd'], 2) : 'N/A' }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Heating Value of HFO </th>
						<td class="text-center">KJ/Kg</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['heating_value_hfo']['this_month'], 3) }}</td>
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['heating_value_hfo']['ytd'], 3) }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Net Heat Rate based on Flowmeter </th>
						<td class="text-center">KJ/KWh</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ is_numeric($plant_info[$plant->id]['net_heat_rate_flowmeter']['this_month']) ? number_format($plant_info[$plant->id]['net_heat_rate_flowmeter']['this_month'], 3) : 'N/A' }}</td>
							<td class="text-center align-middle">{{ is_numeric($plant_info[$plant->id]['net_heat_rate_flowmeter']['ytd']) ? number_format($plant_info[$plant->id]['net_heat_rate_flowmeter']['ytd'], 3) : 'N/A' }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Net Heat Rate based on Tank sounding </th>
						<td class="text-center">KJ/KWh</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ is_numeric($plant_info[$plant->id]['net_heat_rate_tank']['this_month']) ? number_format($plant_info[$plant->id]['net_heat_rate_tank']['this_month'], 3) : 'N/A' }}</td>
							<td class="text-center align-middle">{{ is_numeric($plant_info[$plant->id]['net_heat_rate_tank']['ytd']) ? number_format($plant_info[$plant->id]['net_heat_rate_tank']['ytd'], 3) : 'N/A' }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Total Lube oil Consumption </th>
						<td class="text-center">Kg</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['total_lube_oil_consumption']['this_month'], 3) }}</td>
							<td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['total_lube_oil_consumption']['ytd'], 3) }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Specific Lube oil Consumption </th>
						<td class="text-center">gm/KWh</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ is_numeric($plant_info[$plant->id]['specific_lube_oil_consumption']['this_month']) ? number_format($plant_info[$plant->id]['specific_lube_oil_consumption']['this_month'], 3) : 'N/A' }}</td>
							<td class="text-center align-middle">{{ is_numeric($plant_info[$plant->id]['specific_lube_oil_consumption']['ytd']) ? number_format($plant_info[$plant->id]['specific_lube_oil_consumption']['ytd'], 3) : 'N/A' }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Turbine Information :</th>
						<td class="text-center"></td>
						@foreach($plants as $plant)
							<td class="text-center align-middle"></td>
							<td class="text-center align-middle"></td>
						@endforeach
					</tr>
					<tr>
						<th>Total Generation </th>
						<td class="text-center">MWh</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['turbine_total_generation']['this_month'] ? number_format($plant_info[$plant->id]['turbine_total_generation']['this_month'], 3) : 'N/A' }}</td>
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['turbine_total_generation']['ytd'] ? number_format($plant_info[$plant->id]['turbine_total_generation']['ytd'], 3) : 'N/A' }}</td>
						@endforeach
					</tr>
					<tr>
						<th>% of Co- Generation </th>
						<td class="text-center">%</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['turbine_co_generation']['this_month'] ? number_format($plant_info[$plant->id]['turbine_co_generation']['this_month'], 3) : 'N/A' }}</td>
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['turbine_co_generation']['ytd'] ? number_format($plant_info[$plant->id]['turbine_co_generation']['ytd'], 3) : 'N/A' }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Running Hour </th>
						<td class="text-center">Hr</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['turbine_running_hour']['this_month'] }}</td>
							<td class="text-center align-middle">{{ $plant_info[$plant->id]['turbine_running_hour']['ytd'] }}</td>
						@endforeach
					</tr>
					<tr>
						<th>Start </th>
						<td class="text-center">Nos</td>
						@foreach($plants as $plant)
							<td class="text-center align-middle"></td>
							<td class="text-center align-middle"></td>
						@endforeach
					</tr>
				</tbody>
			</table>
		</div>		
		@endif
	</div><!-- end card-body -->

	@if(request()->all())
	<div class="card-footer">
		<a href="{{ url()->current() }}/print?date_range={{ request()->date_range }}" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a>
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