<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name" class="control-label">Mother Vessel Name {!! validation_error($errors->first('name'),'name') !!}</label>
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder' => 'Enter Mother Vessel Name', 'id' => 'name']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="code" class="control-label">Mother Vessel Code 
                @if(empty($motherVessel))
                {!! validation_error($errors->first('code'),'code') !!}
                @endif
            </label>
            @if(empty($motherVessel))
                {!! Form::text('code', old('code') ? old('code') : (!empty($motherVessel) ? $motherVessel->code : $code), ['class'=>'form-control', 'placeholder' => 'Enter Mother Vessel Code', 'id' => 'code']) !!}
                <small id="codeHelpBlock" class="form-text text-muted">The code may only contain letters and numbers and at least 6 characters long.</small>
            @else
                {!! Form::text('code', $motherVessel->code, ['class'=>'form-control', 'placeholder' => 'Enter Mother Vessel Code', 'id' => 'code', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="address" class="control-label">Mother Vessel Address
            </label>
            {!! Form::textarea('address', null, ['class'=>'form-control', 'placeholder' => 'Enter Mother Vessel Address', 'id' => 'address', 'size' => '30x2']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('contact_person_name', 'Contact Person Name') !!}
            {!! Form::text('contact_person_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Contact Person Name']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('contact_person_phone', 'Contact Person Phone') !!}
            {!! Form::text('contact_person_phone', null, ['class' => 'form-control', 'placeholder' => 'Enter Contact Person Phone']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('contact_person_email', 'Contact Person Email') !!}
            {!! Form::email('contact_person_email', null, ['class' => 'form-control', 'placeholder' => 'Enter Contact Person Email']) !!}
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')

@endsection


