<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name" class="control-label">Fuel Type Name {!! validation_error($errors->first('name'),'name') !!}</label>
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder' => 'Enter Fuel Type Name', 'id' => 'name']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="code" class="control-label">Fuel Type code 
                @if(empty($fuelType))
                {!! validation_error($errors->first('code'),'code') !!}
                @endif
            </label>
            @if(empty($fuelType))
                {!! Form::text('code', old('code') ? old('code') : (!empty($fuelType) ? $fuelType->code : $code), ['class'=>'form-control', 'placeholder' => 'Enter fuel type code', 'id' => 'code']) !!}
                <small id="codeHelpBlock" class="form-text text-muted">The code may only contain letters and numbers and at least 6 characters long.</small>
            @else
                {!! Form::text('code', $fuelType->code, ['class'=>'form-control', 'placeholder' => 'Enter Fuel Type code', 'id' => 'code', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="unit" class="control-label">Fuel Type Unit (Metric Ton/Litre) {!! validation_error($errors->first('unit'),'unit') !!}</label>
            {!! Form::select('unit', config('constants.fuel_unit'), null, ['class'=>'form-control chosen-select', 'placeholder' => 'Enter Fuel Type Unit', 'id' => 'unit', 'min' => 0]) !!}
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')

@endsection


