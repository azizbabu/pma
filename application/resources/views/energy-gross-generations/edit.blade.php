@extends('layouts.master')

@section('title') Edit Energy Gross Generation @endsection 
@section('page_title') Energy Gross Generations @endsection

@section('content')

<div class="container">
	{!! Form::model($energyGrossGeneration, array('url' => 'energy-gross-generations/'.$energyGrossGeneration->id, 'role' => 'form', 'id'=>'energy-gross-generation-edit-form', 'method' => 'PUT')) !!}
		<div class="card margin-top-20">
			<div class="card-header">
				<h3 class="card-title">Edit Energy Gross Generation</h3>
			</div>
			<div class="card-body">
				<div class="details-info">
		        	<div class="row">
		        		<div class="col-sm-6">
		        			<strong>Plant : </strong>{{ $energyGrossGeneration->plant->name }} <br>
		            		<strong>Meter : </strong>{{ $energyGrossGeneration->meter->name }}
		        		</div>
		        		<div class="col-sm-6">
		        			<strong>OP Code: </strong>{{ $energyGrossGeneration->op_code }} <br>
		            		<strong>OP Date: </strong>{{ Carbon::parse($energyGrossGeneration->op_date)->format('d M, Y') }} 
		        		</div>
		        	</div>
		        </div>

				<div class="row margin-top-20">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="export_start_kwh" class="control-label">Export Start {!! validation_error($errors->first('export_start_kwh'),'export_start_kwh') !!}</label>
				            {!! Form::number('export_start_kwh', null, ['class'=>'form-control','id' => 'export_start_kwh', 'min'=>0, 'required' => 'required', 'step' => 'any']) !!}
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="export_end_kwh" class="control-label">Export End {!! validation_error($errors->first('export_end_kwh'),'export_end_kwh') !!}</label>
				            {!! Form::number('export_end_kwh', null, ['class'=>'form-control','id' => 'export_end_kwh', 'min'=>0, 'required' => 'required', 'step' => 'any']) !!}
						</div>
					</div>
				</div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="import_start_kwh" class="control-label">Import Start {!! validation_error($errors->first('import_start_kwh'),'import_start_kwh') !!}</label>
                            {!! Form::number('import_start_kwh', null, ['class'=>'form-control','id' => 'import_start_kwh', 'min'=>0, 'step' => 'any', 'required' => 'required', 'step' => 'any']) !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="import_end_kwh" class="control-label">Import End {!! validation_error($errors->first('import_end_kwh'),'import_end_kwh') !!}</label>
                            {!! Form::number('import_end_kwh', null, ['class'=>'form-control','id' => 'import_end_kwh', 'min'=>0, 'required' => 'required', 'step' => 'any']) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="export_start_kvarh" class="control-label">Export Start {!! validation_error($errors->first('export_start_kvarh'),'export_start_kvarh') !!}</label>
				            {!! Form::number('export_start_kvarh', null, ['class'=>'form-control','id' => 'export_start_kvarh', 'min'=>0, 'required' => 'required', 'step' => 'any']) !!}
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="export_end_kvarh" class="control-label">Export End {!! validation_error($errors->first('export_end_kvarh'),'export_end_kvarh') !!}</label>
				            {!! Form::number('export_end_kvarh', null, ['class'=>'form-control','id' => 'export_end_kvarh', 'min'=>0, 'required' => 'required', 'step' => 'any']) !!}
						</div>
					</div>
				</div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="import_start_kvarh" class="control-label">Import Start {!! validation_error($errors->first('import_start_kvarh'),'import_start_kvarh') !!}</label>
                            {!! Form::number('import_start_kvarh', null, ['class'=>'form-control','id' => 'import_start_kvarh', 'min'=>0, 'step' => 'any', 'required' => 'required', 'step' => 'any']) !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="import_end_kvarh" class="control-label">Import End {!! validation_error($errors->first('import_end_kvarh'),'import_end_kvarh') !!}</label>
                            {!! Form::number('import_end_kvarh', null, ['class'=>'form-control','id' => 'import_end_kvarh', 'min'=>0, 'required' => 'required', 'step' => 'any']) !!}
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

@endsection



