@extends('layouts.master')

@section('title') Report of Daily Operation @endsection 
@section('page_title') Report of Daily Operation @endsection

@section('content')
<div class="card margin-top-20">
	<div class="card-header">
		<h4 class="card-title">Report of Daily Operation</h4>
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
			            {!! Form::text('operation_date', request()->operation_date, ['class' => 'form-control datepicker', 'placeholder' => 'Enter operation date']) !!}
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
		
		@if($dailyEngineGrossGenerationArr || $dailyEnergyMeterBillingsArr)
		<div class="table-responsive">
			<table id="table-genset" class="table table-striped table-bordered table-sm">
			    <thead>
			        <tr>
			        	<th rowspan="2" width="7%" class="text-center align-middle">Genset Gross Generation</th>
			        	@if($dailyEngineGrossGenerationArr)
				        	@foreach($dailyEngineGrossGenerationArr as $key=>$value)
								<th rowspan="2" width="7%" class="text-center align-middle">{{ $value['engine_name'] }} MWH</th>
				        	@endforeach
						@endif

						@if($dailyEnergyMeterBillingsArr)
							@for($i = 0;$i < 2;$i++)
								@foreach($dailyEnergyMeterBillingsArr as $key=>$value)
								<th colspan="2">{{ $value['meter_name'] }}</th>
								@endforeach
							@endfor
						@endif
			        </tr>
			        <tr>
			        	@if($dailyEnergyMeterBillingsArr)
							@foreach($dailyEnergyMeterBillingsArr as $key=>$value)
							<td class="text-center align-middle">Main Billing Meter  - Export (KWH)</td>
							<td class="text-center align-middle">Main Billing Meter  - Import (KWH)</td>
							@endforeach
						@endif

						@if($dailyEnergyMeterBillingsArr)
							@foreach($dailyEnergyMeterBillingsArr as $key=>$value)
							<td class="text-center align-middle">Main Billing Meter  - Export (KVARH)</td>
							<td class="text-center align-middle">Main Billing Meter  - Import (KVARH)</td>
							@endforeach
						@endif
			        </tr>
			    </thead>
			    <tbody>
			    	<tr>
			    		<td class="text-center">{{ Carbon::parse(request()->operation_date)->format('d/m/y') }} @ 00:00 hrs</td>
			    		@if($dailyEngineGrossGenerationArr)
				    		@foreach($dailyEngineGrossGenerationArr as $key=>$value)
								<td class="text-center align-middle">{{ $value['last_day_gross_generation'] }}</td>
			        		@endforeach
		        		@endif

		        		@if($dailyEnergyMeterBillingsArr)
							@foreach($dailyEnergyMeterBillingsArr as $key=>$value)
							<td class="text-center align-middle">{{ $value['export_last_day_kwh'] }}</td>
							<td class="text-center align-middle">{{ $value['import_last_day_kwh'] }}</td>
							@endforeach
						@endif

						@if($dailyEnergyMeterBillingsArr)
							@foreach($dailyEnergyMeterBillingsArr as $key=>$value)
							<td class="text-center align-middle">{{ $value['export_last_day_kvarh'] }}</td>
							<td class="text-center align-middle">{{ $value['import_last_day_kvarh'] }}</td>
							@endforeach
						@endif
		        	</tr>
		        	<tr>
		        		<td class="text-center align-middle">{{ Carbon::parse(request()->operation_date)->format('d/m/y') }} @ 24:00 hrs</td>
		        		
		        		@if($dailyEngineGrossGenerationArr)
			        		@foreach($dailyEngineGrossGenerationArr as $key=>$value)
								<td class="text-center align-middle">{{ $value['to_day_gross_generation'] }}</td>
			        		@endforeach
		        		@endif
						
						@if($dailyEnergyMeterBillingsArr)
							@foreach($dailyEnergyMeterBillingsArr as $key=>$value)
							<td class="text-center align-middle">{{ $value['export_to_day_kwh'] }}</td>
							<td class="text-center align-middle">{{ $value['import_to_day_kwh'] }}</td>
							@endforeach
						@endif

						@if($dailyEnergyMeterBillingsArr)
							@foreach($dailyEnergyMeterBillingsArr as $key=>$value)
							<td class="text-center align-middle">{{ $value['export_to_day_kvarh'] }}</td>
							<td class="text-center align-middle">{{ $value['import_to_day_kvarh'] }}</td>
							@endforeach
						@endif
		        	</tr>
		        	<tr>
		        		<td></td>
		        		@if($dailyEngineGrossGenerationArr)
			        		@foreach($dailyEngineGrossGenerationArr as $key=>$value)
								<td class="text-center align-middle">{{ $value['diff'] }}</td>
			        		@endforeach
		        		@endif

		        		@if(!empty($totalExportKwh))
						<td class="text-center align-middle" colspan="4">{{ $totalExportKwh }}</td>
		        		@endif

		        		@if(!empty($totalExportKvarh))
						<td class="text-center align-middle" colspan="4">{{ $totalExportKvarh }}</td>
		        		@endif
		        	</tr>

		        	@if(!empty($totalImportKwh) && !empty($totalImportKvarh))
					<tr>
						@if($dailyEngineGrossGenerationArr)
						<td class="text-right align-middle" colspan="{{ count($dailyEngineGrossGenerationArr) + 1 }}">Total  import</td>
						@endif
						<td class="text-center align-middle" colspan="3">{{ $totalImportKwh }}</td>
						<td class="text-center align-middle">MWH</td>
						<td class="text-center align-middle" colspan="3">{{ $totalImportKvarh }}</td>
						<td class="text-center align-middle">MVARH</td>
					</tr>
		        	@endif
			    </tbody>
			</table>
		</div>
		@endif

		@if($dailyEngineActivitiesArr)
		<div class="table-responsive margin-top-20">
			<table class="table table-bordered table-striped table-sm">
				<tbody>
					<tr>
						<th rowspan="7" class="align-middle">Running hrs Reading</th>
						@foreach($dailyEngineGrossGenerationArr as $key=>$value)
							<th colspan="3" class="text-center">{{ $value['engine_name'] }}</th>
			        	@endforeach
		        	</tr>
		        	<tr>
		        		@foreach($dailyEngineGrossGenerationArr as $key=>$value)
							<td class="text-center">Start time</td>
							<td class="text-center">Stop time</td>
							<td class="text-center">Equ.hrs</td>
			        	@endforeach
		        	</tr>

					@php
					$i = 0;
					$timeDiff = [];
					@endphp

		        	@for($i=0;$i<$total_row;$i++)
		        	<tr>
		        		@foreach($dailyEngineGrossGenerationArr as $key=>$value)
							<td class="text-center">{{ $dailyEngineActivitiesArr['engine-running'][$key]['start_time'][$i] }}</td>
							<td class="text-center">{{ $dailyEngineActivitiesArr['engine-running'][$key]['stop_time'][$i] }}</td>
							<td class="text-center">{{ $dailyEngineActivitiesArr['engine-running'][$key]['diff_time'][$i] }}</td>
							@php
							$timeDiff[$key][] = $dailyEngineActivitiesArr['engine-running'][$key]['diff_time'][$i];
							@endphp
			        	@endforeach
		        	</tr>
					@endfor

		        	@for($i=0;$i < 2;$i++)
					<tr>
						@for($i=0;$i<$total_engine*3;$i++)
						<td>&nbsp;</td>
						@endfor
					</tr>
		        	@endfor

		        	<tr>
		        		@foreach($dailyEngineGrossGenerationArr as $key=>$value)
							<td class="text-center" colspan="2">Total running hour</td>
							<td class="text-center">{{ getTotalTime($timeDiff[$key]) }}:00</td>
			        	@endforeach
		        	</tr>
				</tbody>
			</table>
		</div>

		<div class="table-responsive margin-top-20">
			<table class="table table-bordered table-striped table-sm">
				<tbody>
					<tr>
						<th rowspan="2" class="align-middle">Engine Outage Calculation</th>
						@foreach($dailyEngineGrossGenerationArr as $key=>$value)
							<th colspan="3" width="15%" class="text-center">{{ $value['engine_name'] }}</th>
			        	@endforeach
			        	<th rowspan="2" class="text-center align-middle">Total</th>
		        	</tr>
		        	<tr>
		        		@foreach($dailyEngineGrossGenerationArr as $key=>$value)
							<td class="text-center">Start time</td>
							<td class="text-center">Stop time</td>
							<td class="text-center">Equ.hrs</td>
			        	@endforeach
		        	</tr>

					@php 
					$timeDiff = []; 
					$totalTimeDiff = [];
					@endphp

		        	@foreach($dailyEngineActivitiesFilterArr as $activity_state => $activity_state_value)
		        		@for($i=0;$i<$total_row;$i++)
						<tr>
							@if($i==0)
								<td class="align-middle" rowspan="{{ $total_row + 1 }}">{{ config('constants.engine_activity_state.'.$activity_state) }}</td>
							@endif

							@foreach($dailyEngineGrossGenerationArr as $key=>$value)
								<td width="15%" class="text-center">{{ $dailyEngineActivitiesFilterArr[$activity_state][$key]['start_time'][$i] }}</td>
								<td width="15%" class="text-center">{{ $dailyEngineActivitiesFilterArr[$activity_state][$key]['stop_time'][$i] }}</td>
								<td width="15%" class="text-center">{{ $dailyEngineActivitiesFilterArr[$activity_state][$key]['diff_time'][$i] }}</td>

								@php 
								$diffTime[$activity_state][$key][] = $dailyEngineActivitiesFilterArr[$activity_state][$key]['diff_time'][$i];
								$totalDiffTime[$activity_state][] = $dailyEngineActivitiesFilterArr[$activity_state][$key]['diff_time'][$i];
								@endphp
			        		@endforeach

			        		@if($i == 0)
			        		<td rowspan="{{ $total_row + 1 }}" class="text-center align-middle">
								{{ getTotalTime($totalDiffTime[$activity_state]) }}:00
							</td>
							@endif
						</tr>
						@endfor

						<tr>
							@foreach($dailyEngineGrossGenerationArr as $key=>$value)
								<td class="text-center" colspan="2">Total running hour</td>
								<td class="text-center">{{ getTotalTime($diffTime[$activity_state][$key]) }}:00</td>
			        		@endforeach
						</tr>
		        	@endforeach
		        </tbody>
		    </table>
		</div>
		@endif

		@if($dailyEnergyMeterBillingsArr)
		<div class="row">
			<div class="col-md-6">
				<div class="table-responsive margin-top-20">
					<table class="table table-bordered table-striped table-sm">
						<tbody>
							<tr>
								<th>Guaranteed Capacity,MWh</th>
								<td class="text-center">{{ number_format(($guaranteed_capacity = $plant->getGuaranteedCapacity()), 2) }}</td>
							</tr>
							<tr>
								<th>Gross Generation, MWh</th>
								<td class="text-center">{{ $sumGrossGeneration }}</td>
							</tr>
							<tr>
								<th>Net Generation, MWh</th>
								<td class="text-center">{{ number_format($totalExportKwh, 2) }}</td>
							</tr>
							<tr>
								<th>Net Import, MWH</th>
								<td class="text-center">{{ number_format($totalImportKwh,2) }}</td>
							</tr>
							<tr>
								<th>Daily Plant Load Factor (PLF), %</th>
								<td class="text-center">{{ number_format($plant_load_factor = ($totalExportKwh/$guaranteed_capacity*100), 2) }}</td>
							</tr>
							<tr>
								<th>Plant Load Factor MTD, %</th>
								<td class="text-center">{{ number_format($plf_mdt,2) }}</td>
							</tr>
							<tr>
								<th>Auxiliary Consumption,MWh</th>
								<td class="text-center">
								{{ $auxiliary_comsumption_mwh = round(($sumGrossGeneration - $totalExportKwh + $plant_load_factor), 2) }}</td>
							</tr>
							<tr>
								<th>Generation from Turbine,%</th>
								<td class="text-center">{{  round(($turbine_diff/$sumGrossGeneration*100),2) }}</td>
							</tr>
							<tr>
								<th>Auxiliary Consumption,%</th>
								<td class="text-center">{{ round(($auxiliary_comsumption_mwh/$sumGrossGeneration*100), 2) }}</td>
							</tr>
							<tr>
								<th>Fuel Consumption ( Flowmeter), MT</th>
								<td class="text-center">{{ round($fuel_consumption_flowmeter, 3) }}</td>
							</tr>
							<tr>
								<th>Fuel Consumption ( Tank), MT</th>
								<td class="text-center">{{ $dailyPlantGeneration->plant_fuel_consumption }}</td>
							</tr>
							<tr>
								<th>Reference LHV, KJ/Kg</th>
								<td class="text-center">{{ $dailyPlantGeneration->reference_lhv }}</td>
							</tr>
							<tr>
								<th>Net Heat Rate, KJ/KWh  (Flowmeter)</th>
								<td class="text-center">{{ number_format(($fuel_consumption_flowmeter * $dailyPlantGeneration->reference_lhv/$totalExportKwh), 2) }}</td>
							</tr>
							<tr>
								<th>Net Heat Rate, KJ/KWh  (Tank)</th>
								<td class="text-center">{{ number_format((($dailyPlantGeneration->plant_fuel_consumption - $dailyPlantGeneration->aux_boiler_hfo_consumption)*$dailyPlantGeneration->reference_lhv/$totalExportKwh), 3) }}</td>
							</tr>
							<tr>
								<th>Total HFO Stock,MT</th>
								<td class="text-center">{{ $dailyPlantGeneration->total_hfo_stock }}</td>
							</tr>
							<tr>
								<th>Total Pumpable HFO Stock,MT	</th>
								<td class="text-center">{{ $dailyPlantGeneration->total_hfo_stock - $plant->tank_dead_stock }}</td>
							</tr>
							<tr>
								<th>Power Factor</th>
								<td class="text-center">{{ round(cos(tan($totalExportKvarh/$totalExportKwh)),2) }}</td>
							</tr>
							<tr>
								<th>Auxiliary Boiler HFO consumption ,MT</th>
								<td class="text-center">{{ round($dailyPlantGeneration->aux_boiler_hfo_consumption, 2) }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			@if($dailyHfoLubeModules->isNotEmpty())
			<div class="col-md-6">
				<div class="table-responsive margin-top-20">
					<table class="table table-bordered table-striped table-sm">
						<thead>
							<tr>
								<th colspan="{{ $total_engine + 1}}" class="text-center">Fuel Booster Module Reading</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Engine </td>
								@foreach($dailyHfoLubeModules as $dailyHfoLubeModule)
								<td class="text-center">{{ $dailyHfoLubeModule->engine->name }}</td>
								@endforeach
							</tr>
							<tr>
								<td>Total, M T</td>
								@foreach($dailyHfoLubeModules as $dailyHfoLubeModule)
								<td class="text-center">{{ round($dailyHfoLubeModule->hfo,3) }}</td>
								@endforeach
							</tr>
							<tr>
								<th colspan="{{ $total_engine + 1}}" class="text-center">Lube oil consumption</th>
							</tr>
							<tr>
								<td>Lube oil consumption</td>
								@foreach($dailyHfoLubeModules as $dailyHfoLubeModule)
								<td class="text-center">{{ round($dailyHfoLubeModule->lube_oil, 3) }}</td>
								@endforeach
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			@endif
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