<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name" class="control-label">Item Type Name {!! validation_error($errors->first('name'),'name') !!}</label>
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder' => 'Enter Item Type Name', 'id' => 'name']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="code" class="control-label">Item Type code 
                @if(empty($itemGroup))
                {!! validation_error($errors->first('code'),'code') !!}
                @endif
            </label>
            @if(empty($itemGroup))
                {!! Form::text('code', old('code') ? old('code') : (!empty($itemGroup) ? $itemGroup->code : $code), ['class'=>'form-control', 'placeholder' => 'Enter Item Type Code', 'id' => 'code']) !!}
                <small id="codeHelpBlock" class="form-text text-muted">The code may only contain letters and numbers and at least 6 characters long.</small>
            @else
                {!! Form::text('code', $itemGroup->code, ['class'=>'form-control', 'placeholder' => 'Enter Item Type Code', 'id' => 'code', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')

@endsection


