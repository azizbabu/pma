<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name" class="control-label">Terminal Name {!! validation_error($errors->first('name'),'name') !!}</label>
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder' => 'Enter Terminal Name', 'id' => 'name']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="code" class="control-label">Terminal code 
                @if(empty($terminal))
                {!! validation_error($errors->first('code'),'code') !!}
                @endif
            </label>
            @if(empty($terminal))
                {!! Form::text('code', old('code') ? old('code') : (!empty($terminal) ? $terminal->code : $code), ['class'=>'form-control', 'placeholder' => 'Enter Terminal code', 'id' => 'code']) !!}
                <small id="codeHelpBlock" class="form-text text-muted">The code may only contain letters and numbers and at least 6 characters long.</small>
            @else
                {!! Form::text('code', $terminal->code, ['class'=>'form-control', 'placeholder' => 'Enter Terminal code', 'id' => 'code', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="capacity" class="control-label">Terminal Capacity (Metric Ton) {!! validation_error($errors->first('capacity'),'capacity') !!}</label>
            {!! Form::text('capacity', null, ['class'=>'form-control', 'placeholder' => 'Enter Terminal Capacity', 'id' => 'capacity', 'min' => 0]) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="address" class="control-label">Terminal Address
            </label>
            {!! Form::textarea('address', null, ['class'=>'form-control', 'placeholder' => 'Enter Terminal Address', 'id' => 'address', 'size' => '30x2']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('manager_name', 'Manager Name') !!}
            {!! Form::text('manager_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Manager Name']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('manager_phone', 'Manager Phone') !!}
            {!! Form::text('manager_phone', null, ['class' => 'form-control', 'placeholder' => 'Enter Manager Phone']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('manager_email', 'Manager Email') !!}
            {!! Form::text('manager_email', null, ['class' => 'form-control', 'placeholder' => 'Enter Manager Email']) !!}
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')

@endsection


