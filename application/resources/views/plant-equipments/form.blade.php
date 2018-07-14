<div class="row">
    <div class="col-sm-6"><strong>Plant Equipment Code: </strong>{{ $code }}</div>
</div>

<div class="row margin-top-20">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="plant_id" class="control-label">Plant {!! validation_error($errors->first('plant_id'),'plant_id') !!}</label>
            {!! Form::select('plant_id', $plants, null, ['class'=>'form-control chosen-select', 'id' => 'plant_id']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name" class="control-label">Name {!! validation_error($errors->first('name'),'name') !!}</label>
            {!! Form::text('name', null, ['class'=>'form-control', 'id' => 'name', 'placeholder' => 'Enter Plant Equipment Name']) !!}
        </div>
    </div>
</div>

{{--  
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="code" class="control-label">Plant Equipment code 
                @if(empty($plantEquipment))
                {!! validation_error($errors->first('code'),'code') !!}
                @endif
            </label>
            @if(empty($plantEquipment))
                {!! Form::text('code', old('code') ? old('code') : (!empty($plantEquipment) ? $plantEquipment->code : $code), ['class'=>'form-control', 'placeholder' => 'Enter plant equipment code', 'id' => 'code']) !!}
                <small id="codeHelpBlock" class="form-text text-muted">
                The plant equipment code may only contain letters and codes and at least 6 characters long.
                </small>
            @else
                {!! Form::text('code', $plantEquipment->code, ['class'=>'form-control', 'placeholder' => 'Enter Plant code', 'id' => 'code', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
</div>
--}}

@section('custom-style')

@endsection

@section('custom-script')

@endsection


