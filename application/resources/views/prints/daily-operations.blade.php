@extends('prints.master')

@section('title') Item Stock Report @endsection 

@section('content')

<div class="text-center">
    <h3><strong>For the month of {{ Carbon::parse(request()->op_date)->format('F, Y') }}</strong></h3>
    <strong>Date:</strong>{{ Carbon::parse(request()->op_date)->format('d-M-y') }} <br>
    <strong>Plant Name:</strong> {{ $plant->name }}
</div>

@if($engineGrossGenerationsGensetArr || $energyGrossGenerationsArr)
<div class="margin-top-20">
    <table id="table-genset" class="table table-striped table-bordered table-sm">
        <thead>
            <tr>
                <th rowspan="2" width="7%" class="text-center align-middle">Genset Gross Generation</th>
                @if($engineGrossGenerationsGensetArr)
                @foreach($engineGrossGenerationsGensetArr as $key=>$value)
                <th rowspan="2" width="7%" class="text-center align-middle">{{ $value['name'] }} MWH</th>
                @endforeach
                @endif

                @if($energyGrossGenerationsArr)
                @for($i = 0;$i < 2;$i++)
                @foreach($energyGrossGenerationsArr as $key=>$value)
                <th colspan="2">{{ $value['meter_name'] }}</th>
                @endforeach
                @endfor
                @endif
            </tr>
            <tr>
                @if($energyGrossGenerationsArr)
                @foreach($energyGrossGenerationsArr as $key=>$value)
                <td class="text-center align-middle">Main Billing Meter  - Export (KWH)</td>
                <td class="text-center align-middle">Main Billing Meter  - Import (KWH)</td>
                @endforeach
                @endif

                @if($energyGrossGenerationsArr)
                @foreach($energyGrossGenerationsArr as $key=>$value)
                <td class="text-center align-middle">Main Billing Meter  - Export (KVARH)</td>
                <td class="text-center align-middle">Main Billing Meter  - Import (KVARH)</td>
                @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">{{ Carbon::parse(request()->op_date)->format('d/m/y') }} @ 00:00 hrs</td>
                @if($engineGrossGenerationsGensetArr)
                @foreach($engineGrossGenerationsGensetArr as $key=>$value)
                <td class="text-center">{{ $value['start_op_mwh'] }}</td>
                @endforeach
                @endif

                @if($energyGrossGenerationsArr)
                @foreach($energyGrossGenerationsArr as $key=>$value)
                <td>{{ $value['export_start_kwh'] }}</td>
                <td>{{ $value['import_start_kwh'] }}</td>
                @endforeach
                @endif

                @if($energyGrossGenerationsArr)
                @foreach($energyGrossGenerationsArr as $key=>$value)
                <td>{{ $value['export_start_kvarh'] }}</td>
                <td>{{ $value['import_start_kvarh'] }}</td>
                @endforeach
                @endif
            </tr>
            <tr>
                <td class="text-center">{{ Carbon::parse(request()->op_date)->format('d/m/y') }} @ 24:00 hrs</td>

                @if($engineGrossGenerationsGensetArr)
                @foreach($engineGrossGenerationsGensetArr as $key=>$value)
                <td class="text-center">{{ $value['end_op_mwh'] }}</td>
                @endforeach
                @endif

                @if($energyGrossGenerationsArr)
                @foreach($energyGrossGenerationsArr as $key=>$value)
                <td>{{ $value['export_end_kwh'] }}</td>
                <td>{{ $value['import_end_kwh'] }}</td>
                @endforeach
                @endif

                @if($energyGrossGenerationsArr)
                @foreach($energyGrossGenerationsArr as $key=>$value)
                <td>{{ $value['export_end_kvarh'] }}</td>
                <td>{{ $value['import_end_kvarh'] }}</td>
                @endforeach
                @endif
            </tr>
            <tr>
                <td></td>
                @if($engineGrossGenerationsGensetArr)
                @foreach($engineGrossGenerationsGensetArr as $key=>$value)
                <td class="text-center">{{ $value['diff'] }}</td>
                @endforeach
                @endif

                @if(!empty($totalExportKwh))
                <td class="text-center" colspan="4">{{ $totalExportKwh }}</td>
                @endif

                @if(!empty($totalExportKvarh))
                <td class="text-center" colspan="4">{{ $totalExportKvarh }}</td>
                @endif
            </tr>

            @if(!empty($totalImportKwh) && !empty($totalImportKvarh))
            <tr>
                @if($engineGrossGenerationsGensetArr)
                <td class="text-right" colspan="{{ count($engineGrossGenerationsGensetArr) + 1 }}">Total  import</td>
                @endif
                <td class="text-center" colspan="3">{{ $totalImportKwh }}</td>
                <td class="text-center">MWH</td>
                <td class="text-center" colspan="3">{{$totalImportKvarh }}</td>
                <td class="text-center">MVARH</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endif

@if($engineGrossGenerationsRunningArr)
<div class="margin-top-20">
    <table class="table table-bordered table-striped table-sm">
        <tbody>
            <tr>
                <th rowspan="7" class="align-middle">Running hrs Reading</th>
                @foreach($engineGrossGenerationsGensetArr as $key=>$value)
                <th colspan="3" width="20%" class="text-center">{{ $value['name'] }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($engineGrossGenerationsGensetArr as $key=>$value)
                <td class="text-center">Start time</td>
                <td class="text-center">Stop time</td>
                <td class="text-center">Equ.hrs</td>
                @endforeach
            </tr>

            @php
            $i = 0;
            $total = $engineGrossGenerationsRunning->count();
            $timeDiff = [];
            @endphp
            @foreach($engineGrossGenerationsRunning as $engineGrossGeneration)
            @if($i == 0 || $i%$total_engine ==0)
            <tr>
                @endif
                <td class="text-center">
                    @if(($engineGrossGeneration->start_time == '00:00') && ($engineGrossGeneration->end_time == '00:00'))
                    @else
                    {{ Carbon::parse($engineGrossGeneration->op_date)->format('d/m/Y') .' ' . $engineGrossGeneration->start_time }}
                    @endif
                </td>

                <td class="text-center">
                    @if($engineGrossGeneration->start_time == '00:00' && $engineGrossGeneration->end_time == '00:00')
                    @else
                    {{ Carbon::parse($engineGrossGeneration->op_date)->format('d/m/Y') .' ' . $engineGrossGeneration->end_time }}
                    @endif
                </td>
                <td class="text-center">{{ $engineGrossGeneration->diff_time }}:00</td>
                @php 
                $timeDiff[$engineGrossGeneration->engine_id][] = $engineGrossGeneration->diff_time;
                $i++ ;
                @endphp
                @if($i%$total_engine ==0 || $i == $total)
            </tr>
            @endif
            @endforeach

            @for($i=0;$i < 2;$i++)
            <tr>
                @for($i=0;$i<$total_engine*3;$i++)
                <td>&nbsp;</td>
                @endfor
            </tr>
            @endfor

            <tr>
                @foreach($engineGrossGenerationsGensetArr as $key=>$value)
                <td class="text-center" colspan="2">Total running hour</td>
                <td class="text-center">{{ getTotalTime($timeDiff[$value['engine_id']]) }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>
@endif
@endsection

@section('custom-style')

@endsection
