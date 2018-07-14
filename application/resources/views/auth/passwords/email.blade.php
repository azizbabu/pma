@extends('layouts.public_master')

@section('content')
<h4 class="text-muted text-center font-18"><b>Reset Password</b></h4>

<div class="p-3">
    <form class="form-horizontal m-t-20" method="post" action="{{ route('password.email') }}">
        {{ csrf_field() }}

        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Enter your <b>Email</b> and instructions will be sent to you!
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="form-group">
            <div class="col-xs-12">
                <input class="form-control" type="email" name="email" value="{{ old('email') }}" required="" placeholder="Email">

                @if ($errors->has('email'))
                    <span class="invalid-feedback d-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group text-center row m-t-20">
            <div class="col-12">
                <button class="btn btn-info btn-block waves-effect waves-light" type="submit">Send Email</button>
            </div>
        </div>

    </form>
</div>
@endsection
