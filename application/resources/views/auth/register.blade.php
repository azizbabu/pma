@extends('layouts.public_master')

@section('title') Registration @endsection

@section('content')
<h4 class="text-muted text-center font-18"><b>Register</b></h4>

<div class="p-3">
    <form class="form-horizontal m-t-20" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <div class="form-group row">
            <div class="col-12">
                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" name="name" value="{{ old('name') }}" required="" placeholder="Name">

                @if ($errors->has('name'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12">
                <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Phone">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12">
                <input class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" type="text" name="username" value="{{ old('username') }}" required="" placeholder="Username" aria-describedby="usernameHelpBlock">

                @if ($errors->has('username'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
                <small id="usernameHelpBlock" class="form-text text-muted">
                  The username may only contain letters, numbers, and dashes.
                </small>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12">
                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" value="{{ old('email') }}" required="" placeholder="Email">

                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12">
                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" required="" placeholder="Password">

                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif

                <small id="passwordHelpBlock" class="form-text text-muted">
                  Your password must be at least 6 characters long.
                </small>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12">
                <input class="form-control" type="password" name="password_confirmation" required="" placeholder="Confirm Password">
            </div>
        </div>

        <div class="form-group row d-none">
            <div class="col-12">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                    <label class="custom-control-label font-weight-normal" for="customCheck1">I accept <a href="#" class="text-muted">Terms and Conditions</a></label>
                </div>
            </div>
        </div>

        <div class="form-group text-center row m-t-20">
            <div class="col-12">
                <button class="btn btn-info btn-block waves-effect waves-light" type="submit">Register</button>
            </div>
        </div>

        <div class="form-group m-t-10 mb-0 row">
            <div class="col-12 m-t-20 text-center">
                <a href="{{ url('/login') }}" class="text-muted">Already have account?</a>
            </div>
        </div>
    </form>
</div>

@endsection
