<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name" class="control-label">Party Name {!! validation_error($errors->first('name'),'name') !!}</label>
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder' => 'Enter Party Name', 'id' => 'name']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="code" class="control-label">Party Code 
                @if(empty($party))
                {!! validation_error($errors->first('code'),'code') !!}
                @endif
            </label>
            @if(empty($party))
                {!! Form::text('code', old('code') ? old('code') : (!empty($party) ? $party->code : $code), ['class'=>'form-control', 'placeholder' => 'Enter Party Code', 'id' => 'code']) !!}
                <small id="codeHelpBlock" class="form-text text-muted">
                The code may only contain letters and numbers and at least 6 characters long.
                </small>
            @else
                {!! Form::text('code', $party->code, ['class'=>'form-control', 'placeholder' => 'Enter Party Code', 'id' => 'code', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('mobile', 'Mobile') !!}
            {!! Form::text('mobile', null, ['class' => 'form-control', 'placeholder' => 'Enter Mobile Number']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('email', 'Email') !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="address" class="control-label">Party Address
            </label>
            {!! Form::textarea('address', null, ['class'=>'form-control', 'placeholder' => 'Enter Party Address', 'id' => 'address', 'size' => '30x2']) !!}
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
            {!! Form::label('contact_person_mobile', 'Contact Person Mobile') !!}
            {!! Form::text('contact_person_mobile', null, ['class' => 'form-control', 'placeholder' => 'Enter Contact Person Mobile']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('contact_person_email', 'Contact Person Email') !!}
            {!! Form::text('contact_person_email', null, ['class' => 'form-control', 'placeholder' => 'Enter Contact Person Email']) !!}
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')

@endsection


