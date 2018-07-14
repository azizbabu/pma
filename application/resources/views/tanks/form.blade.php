<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="terminal_id" class="control-label">Terminal {!! validation_error($errors->first('terminal_id'),'terminal_id') !!}</label>
            {!! Form::select('terminal_id', $terminals, null, ['class'=>'form-control chosen-select', 'id' => 'terminal_id']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="number" class="control-label">Tank Number 
                @if(empty($tank))
                {!! validation_error($errors->first('number'),'number') !!}
                @endif
            </label>
            @if(empty($tank))
                {!! Form::text('number', old('number') ? old('number') : (!empty($tank) ? $tank->number : $number), ['class'=>'form-control', 'placeholder' => 'Enter Tank Number', 'id' => 'number']) !!}
                <small id="numberHelpBlock" class="form-text text-muted">
                The tank number may only contain letters and numbers and at least 6 characters long.
                </small>
            @else
                {!! Form::text('number', $tank->number, ['class'=>'form-control', 'placeholder' => 'Enter Plant number', 'id' => 'number', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="capacity" class="control-label">Tank Capacity (Metric Ton) {!! validation_error($errors->first('capacity'),'capacity') !!}</label>
            {!! Form::text('capacity', null, ['class'=>'form-control', 'placeholder' => 'Enter Tank Capacity', 'id' => 'capacity', 'min' => 0]) !!}
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')

@endsection


