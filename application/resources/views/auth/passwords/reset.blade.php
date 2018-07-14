@extends('layouts.public_master')

@section('content')
<h4 class="text-muted text-center font-18"><b>Reset Password</b></h4>

<div class="p-3">
    <form class="form-horizontal m-t-20" method="POST" action="{{ route('password.request') }}">
        {{ csrf_field() }}

        <input type="hidden" name="token" value="{{ $token }}">

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
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12">
                <input class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" type="password" name="password_confirmation" required="" placeholder="Confirm Password">
            </div>
        </div>

        <div class="form-group text-center row m-t-20">
            <div class="col-12">
                <button class="btn btn-info btn-block waves-effect waves-light" type="submit">Reset Password</button>
            </div>
        </div>
    </form>
</div>
@endsection
