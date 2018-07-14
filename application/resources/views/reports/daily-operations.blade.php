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
			            {!! Form::text('op_date', request()->op_date, ['class' => 'form-control datepicker', 'placeholder' => 'YYYY-MM-DD']) !!}
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
		
		@if($engineGrossGenerationsGensetArr || $energyGrossGenerationsArr)
		<div class="table-responsive">
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
		<div class="table-responsive margin-top-20">
			<table class="table table-bordered table-striped table-sm">
				<tbody>
					<tr>
						<th rowspan="7" class="align-middle">Running hrs Reading</th>
						@foreach($engineGrossGenerationsGensetArr as $key=>$value)
							<th colspan="3" width="15%" class="text-center">{{ $value['name'] }}</th>
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
	</div><!-- end card-body -->
	@if(request()->all())
	<div class="card-footer">
		<a href="{{ url()->current() }}/print?plant_id={{ request()->plant_id }}&op_date={{ request()->op_date }}" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a>
	</div>
	@endif
</div><!-- end card  -->
@endsection