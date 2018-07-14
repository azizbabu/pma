<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="plant_id" class="control-label">Plant {!! validation_error($errors->first('plant_id'),'plant_id') !!}</label>
            {!! Form::select('plant_id', $plants, null, ['class'=>'form-control chosen-select', 'id' => 'plant_id']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name" class="control-label">Engine Name {!! validation_error($errors->first('name'),'name') !!}</label>
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder' => 'Enter Engine Name', 'id' => 'name']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="number" class="control-label">Engine number 
                @if(empty($engine))
                {!! validation_error($errors->first('number'),'number') !!}
                @endif
            </label>
            @if(empty($engine))
                {!! Form::text('number', old('number') ? old('number') : (!empty($engine) ? $engine->number : $number), ['class'=>'form-control', 'placeholder' => 'Enter Engine number', 'id' => 'number']) !!}
                <small id="numberHelpBlock" class="form-text text-muted">The number may only contain letters and numbers and at least 6 characters long.</small>
            @else
                {!! Form::text('number', $engine->number, ['class'=>'form-control', 'placeholder' => 'Enter Engine number', 'id' => 'number', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="capacity" class="control-label">Engine Capacity {!! validation_error($errors->first('capacity'),'capacity') !!}</label>
            {!! Form::number('capacity', null, ['class'=>'form-control', 'placeholder' => 'Enter Engine Capacity', 'id' => 'capacity', 'min' => 0, 'step' => 'any']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="unit" class="control-label">Engine Unit {!! validation_error($errors->first('unit'),'unit') !!}</label>
            {!! Form::text('unit', null, ['class'=>'form-control', 'placeholder' => 'Enter Engine Unit', 'id' => 'unit']) !!}
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')

@endsection


