@extends('layouts.master')

@section('title') Report of Daily Summary @endsection 
@section('page_title') Report of Daily Summary @endsection

@section('content')
<div class="card margin-top-20">
	<div class="card-header">
		<h4 class="card-title">Report of Daily Summary</h4>
	</div>

	<div class="card-body">
		
		{!! Form::open(['url' => url()->current(), 'role' => 'form', 'id' => 'id']) !!}
            <div class="row">
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
		
		@if($engine_info)
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-sm">
				<thead>
					<tr>
						<th rowspan="2" class="text-center align-middle">Parameter</th>
						<th rowspan="2" class="text-center align-middle">Unit</th>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">{{ getShortNames($plant->name) }}</th>
						@endforeach
					</tr>
					<tr>
						@foreach($plant_objs as $plant)
							@php $engines = $engine_arr[$plant->id] @endphp
							@foreach($engines as $engine_id=>$engine_name)
								<th class="text-center align-middle">{{  $engine_name }}</th>
							@endforeach
						@endforeach
					</tr>
				</thead>
				<tbody>
					<tr>
						<th class="align-middle">Enginewise Generation</th>
						<td class="text-center align-middle">Mwh</td>
						@foreach($plant_objs as $plant)
							@php 
							$engines = $engine_arr[$plant->id];
							$total_generation[$plant->id] = 0;
							@endphp
							@foreach($engines as $engine_id=>$engine_name)
								<th class="text-center align-middle">{{ number_format($engine_info[$plant->id][$engine_id]['gross_generation'], 2) }}</th>

								@php
								$total_generation[$plant->id] += $engine_info[$plant->id][$engine_id]['gross_generation'];
								@endphp
							@endforeach
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">Enginewise Fuel Consumption</th>
						<td class="text-center align-middle">MT</td>
						@foreach($plant_objs as $plant)
							@php $engines = $engine_arr[$plant->id] @endphp
							@foreach($engines as $engine_id=>$engine_name)
								<th class="text-center align-middle">{{ number_format($engine_info[$plant->id][$engine_id]['fuel_consumption'], 3) }}</th>
							@endforeach
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">Fuel Consumption</th>
						<td class="text-center align-middle">MT</td>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">{{ round($plant_info['fuel_consumption'][$plant->id], 3) }}</th>
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">Gauranteed Capacity</th>
						<td class="text-center align-middle">Mwh</td>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">{{ round($plant_info['gauranteed_capacity'][$plant->id], 3) }}</th>
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">Total Generation</th>
						<td class="text-center align-middle">Mwh</td>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">{{ number_format($total_generation[$plant->id], 2) }}</th>
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">Total Dispatched</th>
						<td class="text-center align-middle">Mwh</td>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">
								@php 
								$dailyEnergyMeterBillingInfo = $plant->getDailyEnergyMeterBillingInfo(request()->operation_date);
								@endphp
								{{ $plant_info['total_dispatched'][$plant->id] ? round($plant_info['total_dispatched'][$plant->id], 3) : 'NA' }}
							</th>
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">LHV </th>
						<td class="text-center align-middle">KJ/Kg</td>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">{{ round($plant_info['reference_lhv'][$plant->id], 3) }}</th>
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">Heat Rate Enginewise</th>
						<td class="text-center align-middle">KJ/Kwh</td>
						@foreach($plant_objs as $plant)
							@php $engines = $engine_arr[$plant->id] @endphp
							@foreach($engines as $engine_id=>$engine_name)
								<th class="text-center align-middle">
									{{ $engine_info[$plant->id][$engine_id]['gross_generation'] ? round(($engine_info[$plant->id][$engine_id]['fuel_consumption']/$engine_info[$plant->id][$engine_id]['gross_generation'])*$plant_info['reference_lhv'][$plant->id], 2) : 'x' }}
								</th>
							@endforeach
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">PLF</th>
						<td class="text-center align-middle">%</td>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">
								{{ $plant_info['plf'][$plant->id] ? round($plant_info['plf'][$plant->id], 2):'NA' }}
							</th>
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">PLF Monthly</th>
						<td class="text-center align-middle">%</td>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">{{ round($plant->getPLFMonthToDate($start_date, $to_date), 3) }}</th>
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">Auxiliery Consumption</th>
						<td class="text-center align-middle">%</td>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">{{ round(((($total_generation[$plant->id] - $plant_info['total_dispatched'][$plant->id])/$total_generation[$plant->id])*100), 3) }}</th>
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">Turbine Generation  Consumption</th>
						<td class="text-center align-middle">%</td>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">{{ $plant->getDailyTurbineGeneration(trim(request()->operation_date)) }}</th>
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">Heat Rate (Plant)</th>
						<td class="text-center align-middle">KJ/Kwh</td>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">
								{{ round((($plant_info['fuel_consumption'][$plant->id] - $plant_info['aux_boiler_hfo_consumption'][$plant->id])*($plant_info['reference_lhv'][$plant->id]/$plant_info['total_dispatched'][$plant->id])),2) }}
							</th>
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">Auxiliery boiler HFO consumption Heat Rate (Plant)</th>
						<td class="text-center align-middle">MT</td>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">{{ number_format($plant_info['aux_boiler_hfo_consumption'][$plant->id], 3) }}</th>
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">Pumpable Fuel stock (HFO)</th>
						<td class="text-center align-middle">MT</td>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">{{ $plant_info['pumpable_fuel_stock'][$plant->id] }}</th>
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">Pumpable Fuel stock (DO)</th>
						<td class="text-center align-middle">KL</td>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">&nbsp;</th>
						@endforeach
					</tr>
					<tr>
						<th class="align-middle">Pumpable Lube Oil Stock</th>
						<td class="text-center align-middle">KL</td>
						@foreach($plant_objs as $plant)
							<th class="text-center align-middle" colspan="{{ $total_engine_arr[$plant->id] }}">&nbsp;</th>
						@endforeach
					</tr>
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