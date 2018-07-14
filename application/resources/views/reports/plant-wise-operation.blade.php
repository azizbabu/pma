@extends('layouts.master')

@section('title') Report of Plantwise Operation @endsection 
@section('page_title') Report of Plantwise Operation @endsection

@section('content')
<div class="card margin-top-20">
	<div class="card-header">
		<h4 class="card-title">Report of Plantwise Operation</h4>
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
		
		@if(request()->all())
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-sm">
				<thead>
					<tr>
						<th class="align-middle">Monthly Operation Information</th>
						<th class="align-middle"></th>
						<th class="text-center align-middle" rowspan="2">This month</th>
						<th class="text-center align-middle" rowspan="2">Last Month</th>
						<th class="text-center align-middle" rowspan="2">YTD</th>
					</tr>
					<tr>
						<th class="align-middle">Generation :</th>
						<th class="text-center align-middle">Unit</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Gross Generation</th>
						<td class="text-center">MWh</td>
						<td class="text-center">{{ number_format($gross_generation['this_month'], 3) }}</td>
						<td class="text-center">{{ number_format($gross_generation['last_month'], 3) }}</td>
						<td class="text-center">{{ number_format($gross_generation['ytd'], 3) }}</td>
					</tr>
					<tr>
						<th>Net Generation</th>
						<td class="text-center">MWh</td>
						<td class="text-center">{{ number_format($net_generation['this_month'], 3) }}</td>
						<td class="text-center">{{ number_format($net_generation['last_month'], 3) }}</td>
						<td class="text-center">{{ number_format($net_generation['ytd'], 3) }}</td>
					</tr>
					<tr>
						<th>Energy Import</th>
						<td class="text-center">MWh</td>
						<td class="text-center">{{ number_format($energy_import['this_month'], 3) }}</td>
						<td class="text-center">{{ number_format($energy_import['last_month'], 3) }}</td>
						<td class="text-center">{{ number_format($energy_import['ytd'], 3) }}</td>
					</tr>
					<tr>
						<th>Net Export</th>
						<td class="text-center">MWh</td>
						<td class="text-center">{{ number_format(($net_generation['this_month'] - $energy_import['this_month']), 3) }}</td>
						<td class="text-center">{{ number_format(($net_generation['last_month'] - $energy_import['last_month']), 3) }}</td>
						<td class="text-center">{{ number_format(($net_generation['ytd'] - $energy_import['last_month']), 3) }}</td>
					</tr>
					<tr>
						<th>Station Load</th>
						<td class="text-center">MWh</td>
						<td class="text-center">{{ number_format($station_load['this_month'] = ($gross_generation['this_month'] - $net_generation['this_month'] + $energy_import['this_month']), 3) }}</td>
						<td class="text-center">{{ number_format($station_load['last_month'] =($gross_generation['last_month'] - $net_generation['last_month'] + $energy_import['last_month']), 3) }}</td>
						<td class="text-center">{{ number_format($station_load['ytd'] =($gross_generation['ytd'] - $net_generation['ytd'] + $energy_import['ytd']), 3) }}</td>
					</tr>
					<tr>
						<th>Station Load</th>
						<td class="text-center">%</td>
						<td class="text-center">{{ $gross_generation['this_month'] ? number_format((100 * $station_load['this_month']/$gross_generation['this_month']), 2) : 'N/A' }}</td>
						<td class="text-center">{{ $gross_generation['last_month'] ? number_format((100 * $station_load['last_month']/$gross_generation['last_month']), 2): 'N/A' }}</td>
						<td class="text-center">{{ $gross_generation['ytd'] ? number_format((100 * $station_load['ytd']/$gross_generation['ytd']), 2) : 'N/A' }}</td>
					</tr>
					<tr>
						<th>Plant Load Factor (PLF)</th>
						<td class="text-center">%</td>
						<td class="text-center">{{ $plf['this_month'] ? number_format($plf['this_month'], 3) : 'N/A' }}</td>
						<td class="text-center">{{ $plf['last_month'] ? number_format($plf['last_month'], 3) : 'N/A' }}</td>
						<td class="text-center">{{ $plf['ytd'] ? number_format($plf['ytd'], 3) : 'N/A' }}</td>
					</tr>
					<tr>
						<th>Plant Availability & Reliability :</th>
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center"></td>
					</tr>
					<tr>
						<th>Start </th>
						<td class="text-center">Nos</td>
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center"></td>
					</tr>
					<tr>
						<th>Running Hours </th>
						<td class="text-center">Hrs</td>
						<td class="text-center">{{ $engine_activities['engine-running']['this_month'] }}</td>
						<td class="text-center">{{ $engine_activities['engine-running']['last_month'] }}</td>
						<td class="text-center">{{ $engine_activities['engine-running']['ytd'] }}</td>
					</tr>
					<tr>
						<th>Schedule Outage </th>
						<td class="text-center">Hrs</td>
						<td class="text-center">{{ $engine_activities['schedule-outage']['this_month'] }}</td>
						<td class="text-center">{{ $engine_activities['schedule-outage']['last_month'] }}</td>
						<td class="text-center">{{ $engine_activities['schedule-outage']['ytd'] }}</td>
					</tr>
					<tr>
						<th>Maintenance Outage </th>
						<td class="text-center">Hrs</td>
						<td class="text-center">{{ $engine_activities['maintenance-outage']['this_month'] }}</td>
						<td class="text-center">{{ $engine_activities['maintenance-outage']['last_month'] }}</td>
						<td class="text-center">{{ $engine_activities['maintenance-outage']['ytd'] }}</td>
					</tr>
					<tr>
						<th>Reserve Shutdown </th>
						<td class="text-center">Hrs</td>
						<td class="text-center">{{ $engine_activities['reverse_shut_down']['this_month'] }}</td>
						<td class="text-center">{{ $engine_activities['reverse_shut_down']['last_month'] }}</td>
						<td class="text-center">{{ $engine_activities['reverse_shut_down']['ytd'] }}</td>
					</tr>
					<tr>
						<th>Force Outage (Grid) </th>
						<td class="text-center">Hrs</td>
						<td class="text-center">{{ $engine_activities['force-outage']['this_month'] }}</td>
						<td class="text-center">{{ $engine_activities['force-outage']['last_month'] }}</td>
						<td class="text-center">{{ $engine_activities['force-outage']['ytd'] }}</td>
					</tr>
					<tr>
						<th>Plant Availability </th>
						<td class="text-center">%</td>
						<td class="text-center">{{ number_format($engine_activities['plant_availability']['this_month'], 2) }}</td>
						<td class="text-center">{{ number_format($engine_activities['plant_availability']['last_month'], 2) }}</td>
						<td class="text-center">{{ number_format($engine_activities['plant_availability']['ytd'], 2) }}</td>
					</tr>
					<tr>
						<th>Plant Reliability </th>
						<td class="text-center">%</td>
						<td class="text-center">{{ number_format($engine_activities['plant_reliability']['this_month'], 2) }}</td>
						<td class="text-center">{{ number_format($engine_activities['plant_reliability']['last_month'], 2) }}</td>
						<td class="text-center">{{ number_format($engine_activities['plant_reliability']['ytd'], 2) }}</td>
					</tr>
					<tr>
						<th>Plant Utilization </th>
						<td class="text-center">%</td>
						<td class="text-center">{{ number_format($engine_activities['plant_utilization']['this_month'], 2) }}</td>
						<td class="text-center">{{ number_format($engine_activities['plant_utilization']['last_month'], 2) }}</td>
						<td class="text-center">{{ number_format($engine_activities['plant_utilization']['ytd'], 2) }}</td>
					</tr>
					<tr>
						<th>Fuel Consumption & Heat Rate :</th>
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center"></td>
					</tr>
					<tr>
						<th>Total Fuel Consumption ( Flowmeter ) </th>
						<td class="text-center">MT</td>
						<td class="text-center">{{ round($fuel_consumption_heat_rate['total_fuel_consumption_flowmeter']['this_month'], 3) }}</td>
						<td class="text-center">{{ round($fuel_consumption_heat_rate['total_fuel_consumption_flowmeter']['last_month'], 3) }}</td>
						<td class="text-center">{{ round($fuel_consumption_heat_rate['total_fuel_consumption_flowmeter']['ytd'], 3) }}</td>
					</tr>
					<tr>
						<th>Total Fuel Consumption(Tank Sounding) </th>
						<td class="text-center">MT</td>
						<td class="text-center">{{ round($fuel_consumption_heat_rate['total_fuel_consumption_tank']['this_month'], 3) }}</td>
						<td class="text-center">{{ round($fuel_consumption_heat_rate['total_fuel_consumption_tank']['last_month'], 3) }}</td>
						<td class="text-center">{{ round($fuel_consumption_heat_rate['total_fuel_consumption_tank']['ytd'], 3) }}</td>
					</tr>
					<tr>
						<th>Auxiliary Boiler HFO Consmp. (Assumption) </th>
						<td class="text-center">MT</td>
						<td class="text-center">{{ number_format($fuel_consumption_heat_rate['aux_boiler_hfo_consumption']['this_month'], 3) }}</td>
						<td class="text-center">{{ number_format($fuel_consumption_heat_rate['aux_boiler_hfo_consumption']['last_month'], 3) }}</td>
						<td class="text-center">{{ number_format($fuel_consumption_heat_rate['aux_boiler_hfo_consumption']['ytd'], 3) }}</td>
					</tr>
					<tr>
						<th>Sludge Production </th>
						<td class="text-center">%</td>
						<td class="text-center">{{ is_numeric($fuel_consumption_heat_rate['sludge_production']['this_month']) ? number_format($fuel_consumption_heat_rate['sludge_production']['this_month'], 2) : $fuel_consumption_heat_rate['sludge_production']['this_month'] }}</td>
						<td class="text-center">{{ is_numeric($fuel_consumption_heat_rate['sludge_production']['last_month']) ? number_format($fuel_consumption_heat_rate['sludge_production']['last_month'], 2) : $fuel_consumption_heat_rate['sludge_production']['last_month'] }}</td>
						<td class="text-center">{{ is_numeric($fuel_consumption_heat_rate['sludge_production']['ytd']) ? number_format($fuel_consumption_heat_rate['sludge_production']['ytd'], 2) : $fuel_consumption_heat_rate['sludge_production']['ytd'] }}</td>
					</tr>
					<tr>
						<th>Heating Value of HFO </th>
						<td class="text-center">KJ/Kg</td>
						<td class="text-center">{{ number_format($fuel_consumption_heat_rate['heating_value_hfo']['this_month'], 3) }}</td>
						<td class="text-center">{{ number_format($fuel_consumption_heat_rate['heating_value_hfo']['last_month'], 3) }}</td>
						<td class="text-center">{{ number_format($fuel_consumption_heat_rate['heating_value_hfo']['ytd'], 3) }}</td>
					</tr>
					<tr>
						<th>Net Heat Rate based on Flowmeter </th>
						<td class="text-center">KJ/KWh</td>
						<td class="text-center">{{ is_numeric($fuel_consumption_heat_rate['net_heat_rate_flowmeter']['this_month']) ? number_format($fuel_consumption_heat_rate['net_heat_rate_flowmeter']['this_month'], 3) : $fuel_consumption_heat_rate['net_heat_rate_flowmeter']['this_month'] }}</td>
						<td class="text-center">{{ is_numeric($fuel_consumption_heat_rate['net_heat_rate_flowmeter']['last_month']) ? number_format($fuel_consumption_heat_rate['net_heat_rate_flowmeter']['last_month'], 3) : $fuel_consumption_heat_rate['net_heat_rate_flowmeter']['last_month'] }}</td>
						<td class="text-center">{{ is_numeric($fuel_consumption_heat_rate['net_heat_rate_flowmeter']['ytd']) ? number_format($fuel_consumption_heat_rate['net_heat_rate_flowmeter']['ytd'], 3) : $fuel_consumption_heat_rate['net_heat_rate_flowmeter']['ytd'] }}</td>
					</tr>
					<tr>
						<th>Net Heat Rate based on Tank sounding </th>
						<td class="text-center">KJ/KWh</td>
						<td class="text-center">{{ is_numeric($fuel_consumption_heat_rate['net_heat_rate_tank']['this_month']) ? number_format($fuel_consumption_heat_rate['net_heat_rate_tank']['this_month'], 3) : $fuel_consumption_heat_rate['net_heat_rate_tank']['this_month'] }}</td>
						<td class="text-center">{{ is_numeric($fuel_consumption_heat_rate['net_heat_rate_tank']['last_month']) ? number_format($fuel_consumption_heat_rate['net_heat_rate_tank']['last_month'], 3) : $fuel_consumption_heat_rate['net_heat_rate_tank']['last_month'] }}</td>
						<td class="text-center">{{ is_numeric($fuel_consumption_heat_rate['net_heat_rate_tank']['ytd']) ? number_format($fuel_consumption_heat_rate['net_heat_rate_tank']['ytd'], 3) : $fuel_consumption_heat_rate['net_heat_rate_tank']['ytd'] }}</td>
					</tr>
					<tr>
						<th>Total Lube oil Consumption </th>
						<td class="text-center">Kg</td>
						<td class="text-center">{{ round($fuel_consumption_heat_rate['total_lube_oil_consumption']['this_month'], 3) }}</td>
						<td class="text-center">{{ round($fuel_consumption_heat_rate['total_lube_oil_consumption']['last_month'], 3) }}</td>
						<td class="text-center">{{ round($fuel_consumption_heat_rate['total_lube_oil_consumption']['ytd'], 3) }}</td>
					</tr>
					<tr>
						<th>Specific Lube oil Consumption </th>
						<td class="text-center">gm/KWh</td>
						<td class="text-center">{{ is_numeric($fuel_consumption_heat_rate['specific_lube_oil_consumption']['this_month']) ? number_format($fuel_consumption_heat_rate['specific_lube_oil_consumption']['this_month'], 3) : $fuel_consumption_heat_rate['specific_lube_oil_consumption']['this_month'] }}</td>
						<td class="text-center">{{ is_numeric($fuel_consumption_heat_rate['specific_lube_oil_consumption']['last_month']) ? number_format($fuel_consumption_heat_rate['specific_lube_oil_consumption']['last_month'], 3) : $fuel_consumption_heat_rate['specific_lube_oil_consumption']['last_month'] }}</td>
						<td class="text-center">{{ is_numeric($fuel_consumption_heat_rate['specific_lube_oil_consumption']['ytd']) ? number_format($fuel_consumption_heat_rate['specific_lube_oil_consumption']['ytd'], 3) : $fuel_consumption_heat_rate['specific_lube_oil_consumption']['ytd'] }}</td>
					</tr>
					<tr>
						<th>Turbine Information :</th>
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center"></td>
					</tr>
					<tr>
						<th>Total Generation </th>
						<td class="text-center">MWh</td>
						<td class="text-center">{{ $turbine_info['total_generation']['this_month'] ? number_format($turbine_info['total_generation']['this_month'], 3) : 'N/A' }}</td>
						<td class="text-center">{{ $turbine_info['total_generation']['last_month'] ? number_format($turbine_info['total_generation']['last_month'], 3) : 'N/A' }}</td>
						<td class="text-center">{{ $turbine_info['total_generation']['ytd'] ? number_format($turbine_info['total_generation']['ytd'], 3) :'N/A' }}</td>
					</tr>
					<tr>
						<th>% of Co- Generation </th>
						<td class="text-center">%</td>
						<td class="text-center">{{ $turbine_info['co_generation']['this_month'] ? number_format($turbine_info['co_generation']['this_month'], 3) : 'N/A' }}</td>
						<td class="text-center">{{ $turbine_info['co_generation']['last_month'] ? number_format($turbine_info['co_generation']['last_month'], 3) : 'N/A' }}</td>
						<td class="text-center">{{ $turbine_info['co_generation']['ytd'] ? number_format($turbine_info['co_generation']['ytd'], 3) :'N/A' }}</td>
					</tr>
					<tr>
						<th>Running Hour </th>
						<td class="text-center">Hr</td>
						<td class="text-center">{{ $turbine_info['running_hour']['this_month'] }}</td>
						<td class="text-center">{{ $turbine_info['running_hour']['last_month'] }}</td>
						<td class="text-center">{{ $turbine_info['running_hour']['ytd'] }}</td>
					</tr>
					<tr>
						<th>Start </th>
						<td class="text-center">Nos</td>
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center"></td>
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