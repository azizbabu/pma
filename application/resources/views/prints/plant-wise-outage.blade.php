@extends('prints.master')

@section('title') Plantwise Outage Report @endsection 

@section('content')

<div class="text-center">
    <h2><strong>Monthly Outage Summary</strong></h2>
    <h4><strong>{{ $plant->name }}</strong></h4> <br>
</div>

@if(request()->all())
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

@endsection

@section('custom-style')

@endsection
