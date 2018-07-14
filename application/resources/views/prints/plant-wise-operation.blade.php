@extends('prints.master')

@section('title') Plantwise Operation Report @endsection 

@section('content')

<div class="text-center">
    <h2><strong>Monthly Operation Summary</strong></h2>
    <h4><strong>{{ $plant->name }}</strong></h4> <br>
    <span>From {{ Carbon::parse($from_date)->format('dS M Y') }} 00:00 hrs to {{ Carbon::parse($to_date)->format('dS M Y') }} 24:00 hrs</span>
</div>

@if(request()->all())
    <div class="margin-top-20">
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

@endsection

@section('custom-style')

@endsection
