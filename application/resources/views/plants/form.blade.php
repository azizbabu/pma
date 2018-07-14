<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name" class="control-label">Plant Name {!! validation_error($errors->first('name'),'name') !!}</label>
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder' => 'Enter Plant Name', 'id' => 'name']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="code" class="control-label">Plant code 
                @if(empty($plant))
                {!! validation_error($errors->first('code'),'code') !!}
                @endif
            </label>
            @if(empty($plant))
                {!! Form::text('code', old('code') ? old('code') : (!empty($plant) ? $plant->code : $code), ['class'=>'form-control', 'placeholder' => 'Enter Plant code', 'id' => 'code']) !!}
                <small id="codeHelpBlock" class="form-text text-muted">The code may only contain letters and numbers and at least 6 characters long.</small>
            @else
                {!! Form::text('code', $plant->code, ['class'=>'form-control', 'placeholder' => 'Enter Plant code', 'id' => 'code', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="capacity" class="control-label">Plant Capacity (Metric Ton) {!! validation_error($errors->first('capacity'),'capacity') !!}</label>
            {!! Form::text('capacity', null, ['class'=>'form-control', 'placeholder' => 'Enter Plant Capacity', 'id' => 'capacity', 'min' => 0]) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="address" class="control-label">Plant Address
            </label>
            {!! Form::textarea('address', null, ['class'=>'form-control', 'placeholder' => 'Enter Plant Address', 'id' => 'address', 'size' => '30x2']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="no_of_generating_unit" class="control-label">No of Generating Unit {!! validation_error($errors->first('no_of_generating_unit'),'no_of_generating_unit') !!}</label>
            {!! Form::number('no_of_generating_unit', null, ['class'=>'form-control', 'placeholder' => 'Enter No of Generating Unit', 'id' => 'no_of_generating_unit', 'min' => 1]) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="code_date" class="control-label">Code Date {!! validation_error($errors->first('code_date'),'code_date') !!}
            </label>
            {!! Form::text('code_date', null, ['class'=>'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'id' => 'code_date']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="tank_dead_stock" class="control-label">HFO Tank Dead Stock (MT){!! validation_error($errors->first('tank_dead_stock'),'tank_dead_stock') !!}</label>
            {!! Form::number('tank_dead_stock', null, ['class'=>'form-control', 'placeholder' => 'Enter Tank Dead Stock', 'id' => 'tank_dead_stock', 'min' => 1]) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="energy_meter_multification_factor" class="control-label">Energy Meter Multification Factor {!! validation_error($errors->first('energy_meter_multification_factor'),'energy_meter_multification_factor') !!}
            </label>
            {!! Form::number('energy_meter_multification_factor', null, ['class'=>'form-control', 'placeholder' => 'Enter Energy Meter Multification Factor', 'id' => 'energy_meter_multification_factor', 'min' => 1]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="hfo_storage_tank_number" class="control-label">HFO Storage Tank Number {!! validation_error($errors->first('hfo_storage_tank_number'),'hfo_storage_tank_number') !!}</label>
            {!! Form::number('hfo_storage_tank_number', null, ['class'=>'form-control', 'placeholder' => 'Enter HFO Storage Tank Number', 'id' => 'hfo_storage_tank_number', 'min' => 1]) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="hfo_buffer_tank_number" class="control-label">HFO Buffer Tank Number {!! validation_error($errors->first('hfo_buffer_tank_number'),'hfo_buffer_tank_number') !!}
            </label>
            {!! Form::number('hfo_buffer_tank_number', null, ['class'=>'form-control', 'placeholder' => 'Enter HFO Buffer Tank Number', 'id' => 'hfo_buffer_tank_number', 'min' => 1]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="hfo_service_tank_number" class="control-label">HFO Service Tank Number {!! validation_error($errors->first('hfo_service_tank_number'),'hfo_service_tank_number') !!}</label>
            {!! Form::number('hfo_service_tank_number', null, ['class'=>'form-control', 'placeholder' => 'Enter HFO Service Tank Number', 'id' => 'hfo_service_tank_number', 'min' => 1]) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="diesel_tank_number" class="control-label">HFO Diesel Tank Number {!! validation_error($errors->first('diesel_tank_number'),'diesel_tank_number') !!}
            </label>
            {!! Form::number('diesel_tank_number', null, ['class'=>'form-control', 'placeholder' => 'Enter HFO Diesel Tank Number', 'id' => 'diesel_tank_number', 'min' => 1]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="lube_oil_storage_tank_number" class="control-label">Lube Oil Storage Tank Oil {!! validation_error($errors->first('lube_oil_storage_tank_number'),'lube_oil_storage_tank_number') !!}</label>
            {!! Form::number('lube_oil_storage_tank_number', null, ['class'=>'form-control', 'placeholder' => 'Lube Oil Storage Tank Oil', 'id' => 'lube_oil_storage_tank_number', 'min' => 1]) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="lube_oil_maintenance_tank_number" class="control-label">Lube Oil Maintenance Tank Number
            {!! validation_error($errors->first('lube_oil_maintenance_tank_number'),'lube_oil_maintenance_tank_number') !!}</label>
            {!! Form::number('lube_oil_maintenance_tank_number', null, ['class'=>'form-control', 'placeholder' => 'Enter Lube Oil Maintenance Tank Number', 'id' => 'lube_oil_maintenance_tank_number', 'min' => 1]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="permissible_outage" class="control-label">Permissible Outage (hr){!! validation_error($errors->first('permissible_outage'),'permissible_outage') !!}
            </label>
            {!! Form::number('permissible_outage', null, ['class'=>'form-control', 'placeholder' => 'Enter Permissible Outage', 'id' => 'permissible_outage', 'min' => 1]) !!}
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')

@endsection


