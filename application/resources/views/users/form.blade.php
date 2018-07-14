@unless(isset($is_profile_page))
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="role_id" class="control-label">Role {!! validation_error($errors->first('role_id'),'role_id') !!}</label>
            {!! Form::select('role_id', $roles, null, ['class'=>'form-control chosen-select','id' => 'role_id']) !!}
        </div>
    </div>
</div>
@endunless

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name" class="control-label">Name {!! validation_error($errors->first('name'),'name') !!}</label>
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder' => 'Enter Name', 'id' => 'name']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="phone" class="control-label">Phone </label>
            {!! Form::text('phone', null, ['class'=>'form-control', 'placeholder' => 'Enter Phone', 'id' => 'phone']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="username" class="control-label">Username 
            
            @if(empty($user))
                {!! validation_error($errors->first('username'),'username') !!}
            @endif
            </label>

            @if(empty($user))
                {!! Form::text('username', null, ['class'=>'form-control', 'placeholder' => 'Enter Username', 'id' => 'username']) !!}
                <small id="usernameHelpBlock" class="form-text text-muted">
                    The username may only contain letters, numbers, and dashes.
                </small>
            @else
                {!! Form::text('username', null, ['class'=>'form-control', 'placeholder' => 'Enter Username', 'id' => 'username', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="email" class="control-label">Email 
            @if(empty($user))
                {!! validation_error($errors->first('email'),'email') !!}
            @endif
            </label>
            @if(empty($user))
                {!! Form::text('email', null, ['class'=>'form-control', 'placeholder' => 'Enter Email', 'id' => 'email']) !!}
            @else
                {!! Form::text('email', null, ['class'=>'form-control', 'placeholder' => 'Enter Email', 'id' => 'email', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
</div>

<div class="row">
    @unless(empty($user))
    <div class="col-sm-12">
        <small id="usernameHelpBlock" class="form-text text-muted">If you do not update password, keep these fields empty. </small>
    </div>
    @endunless
    <div class="col-sm-6">
        <div class="form-group">
            <label for="password" class="control-label">Password 
            @if(empty($user))
                {!! validation_error($errors->first('password'),'password') !!}
            @else
                {!! validation_error($errors->first('password'),'password', true) !!}
            @endif
            </label>
            {!! Form::password('password', ['class'=>'form-control', 'placeholder' => 'Enter Password', 'id' => 'password']) !!}
            
            <small id="passwordHelpBlock" class="form-text text-muted">
              Your password must be at least 6 characters long.
            </small>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="password_confirmation" class="control-label">Confirm Password 
            @if(empty($user))
                {!! validation_error($errors->first('password_confirmation'),'password_confirmation') !!}
            @endif
            </label>
            {!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder' => 'Enter Confirm Password', 'id' => 'password_confirmation']) !!}
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')

@endsection


