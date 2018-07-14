@extends('layouts.public_master')

@section('title') Login @endsection

@section('content')


<h4 class="text-muted text-center font-18"><b>Sign In</b></h4>

<div class="p-3">
    <form class="form-horizontal m-t-20" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        <div class="form-group row">
            <div class="col-12">
                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" name="email" value="{{ old('email') }}" placeholder="Email" required="">

                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12">
                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" placeholder="Password" aria-describedby="passwordHelpBlock" required="">

                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="customCheck1">Remember me</label>
                </div>
            </div>
        </div>

        <div class="form-group text-center row m-t-20">
            <div class="col-12">
                <button class="btn btn-info btn-block waves-effect waves-light" type="submit">Log In</button>
            </div>
        </div>

        <div class="form-group m-t-10 mb-0 row">
            <div class="col-sm-7 m-t-20">
                <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock    "></i> Forgot your password?</a>
            </div>
            <div class="col-sm-5 m-t-20">
                <a href="{{ url('/register') }}" class="text-muted"><i class="mdi mdi-account-circle"></i> Create an account</a>
            </div>
        </div>
    </form>
</div>
@endsection