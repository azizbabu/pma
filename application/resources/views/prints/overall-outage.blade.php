@extends('prints.master')

@section('title') Monthly Outage Report @endsection 

@section('content')

<div class="text-center">
    <h2><strong>Monthly Outage Summary</strong></h2>
    <span>From {{ Carbon::parse($from_date)->format('dS M Y') }} 00:00 hrs to {{ Carbon::parse($to_date)->format('dS M Y') }} 24:00 hrs</span>
</div>

@if($plant_info)
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th class="text-center align-middle"></th>
                    <th class="text-center align-middle"></th>
                    @foreach($plants as $plant)
                        <th class="text-center align-middle">{{ getShortNames($plant->name) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="align-middle">Date of COD</th>
                    <td class="text-center align-middle"></td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">&nbsp;</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">Dependable Capacity as per PPA</th>
                    <td class="text-center align-middle">MW</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ $plant_info[$plant->id]['dependable_capacity'] }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">Net Generation</th>
                    <td class="text-center align-middle">MWH</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['net_generation'], 3) }}</td>
                    @endforeach
                </tr>
                <tr>

                    <th class="align-middle">Running hr</th>
                    <td class="text-center align-middle">hr</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ $plant_info[$plant->id]['engine-running'] }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">Maintenance Outage</th>
                    <td class="text-center align-middle">hr</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ $plant_info[$plant->id]['maintenance-outage'] }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">Scheduled Outage</th>
                    <td class="text-center align-middle">hr</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ $plant_info[$plant->id]['schedule-outage'] }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">Force Outage for Grid</th>
                    <td class="text-center align-middle">hr</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ $plant_info[$plant->id]['force-outage'] }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">This month outage including Grid</th>
                    <td class="text-center align-middle">hr</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ $plant_info[$plant->id]['this_month_outage_including_grid'] }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">This month  outage  Excluding  Grid</th>
                    <td class="text-center align-middle">hr</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ $plant_info[$plant->id]['this_month_outage_excluding_grid'] }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">Reserve Shut down in hr</th>
                    <td class="text-center align-middle">hr</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ $plant_info[$plant->id]['reverse_shut_down'] }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">Total Permissible Outage</th>
                    <td class="text-center align-middle">hr</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ $plant_info[$plant->id]['total_permissible_outage'] }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">YTD Outage (Including Grid)</th>
                    <td class="text-center align-middle">hr</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ $plant_info[$plant->id]['ytd_outage_including_grid'] }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">YTD  Outage (Excluding Grid)</th>
                    <td class="text-center align-middle">hr</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ $plant_info[$plant->id]['ytd_outage_excluding_grid'] }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">Remaining Permissible Outage for this year  (including Grid)</th>
                    <td class="text-center align-middle">hr</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ $plant_info[$plant->id]['remaining_permissible_outage_including_grid'] }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">Remaining Permissible Outage for this year (Excluding  Grid)</th>
                    <td class="text-center align-middle">hr</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ $plant_info[$plant->id]['remaining_permissible_outage_excluding_grid'] }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">Remaining Permissible Outage for this year  (including Grid)</th>
                    <td class="text-center align-middle">MWh</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['remaining_permissible_outage_including_grid_mwh'], 3) }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th class="align-middle">Remaining Permissible Outage for this year (Excluding  Grid)</th>
                    <td class="text-center align-middle">MWh</td>
                    @foreach($plants as $plant)
                        <td class="text-center align-middle">{{ number_format($plant_info[$plant->id]['remaining_permissible_outage_excluding_grid_mwh'], 3) }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>  
@endif

@endsection

@section('custom-style')

@endsection
