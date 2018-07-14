@extends('layouts.master')

@section('title') Edit Energy Meter Generation @endsection 
@section('page_title') Energy Meter Generations @endsection

@section('content')

<div class="container">
	{!! Form::model($energyMeterGeneration, array('url' => 'energy-meter-generations/'.$energyMeterGeneration->id, 'role' => 'form', 'id'=>'energy-meter-generation-edit-form', 'method' => 'PUT')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Edit Energy Meter Generation</h3>
			</div>
			<div class="card-body">
				<div class="details-info">
		        	<div class="row">
		        		<div class="col-sm-6">
		        			<strong>Plant : </strong>{{ $energyMeterGeneration->plant->name }} <br>
		            		<strong>Meter : </strong>{{ $energyMeterGeneration->meter->name }}
		        		</div>
		        		<div class="col-sm-6">
		        			<strong>Gen Code: </strong>{{ $energyMeterGeneration->gen_code }} <br>
		            		<strong>Gen Date: </strong>{{ Carbon::parse($energyMeterGeneration->gen_date)->format('d M, Y') }} 
		        		</div>
		        	</div>
		        </div>

				<div class="row margin-top-20">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="export_start" class="control-label">Export Start {!! validation_error($errors->first('export_start'),'export_start') !!}</label>
				            {!! Form::number('export_start', null, ['class'=>'form-control','id' => 'export_start', 'required', 'step' => 'any', 'data-parsley-min' => 1]) !!}
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="export_end" class="control-label">Export End {!! validation_error($errors->first('export_end'),'export_end') !!}</label>
				            {!! Form::number('export_end', null, ['class'=>'form-control','id' => 'export_end', 'required', 'step' => 'any', 'data-parsley-min' => 1]) !!}
						</div>
					</div>
				</div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="import_start" class="control-label">Import Start {!! validation_error($errors->first('import_start'),'import_start') !!}</label>
                            {!! Form::number('import_start', null, ['class'=>'form-control','id' => 'import_start', 'step' => 'any', 'required', 'step' => 'any', 'data-parsley-min' => 1]) !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="import_end" class="control-label">Import End {!! validation_error($errors->first('import_end'),'import_end') !!}</label>
                            {!! Form::number('import_end', null, ['class'=>'form-control','id' => 'import_end', 'required', 'step' => 'any', 'data-parsley-min' => 1]) !!}
                        </div>
                    </div>
                </div>
			</div>

			<div class="card-footer">
				<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
			</div>
		</div>		
	{!! Form::close() !!}
</div>

@endsection

@section('custom-style')

@endsection

@section('custom-script')
{!! Html::script($assets. '/plugins/parsleyjs/parsley.min.js') !!}

<script>
	(function() {
		$('#energy-meter-generation-edit-form').parsley();
	})();
</script>
@endsection



