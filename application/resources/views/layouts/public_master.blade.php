<!DOCTYPE html>
<html>
    <head>
        @include('layouts.public_head')
    </head>


    <body>

        <!-- Begin page -->
        <div class="wrapper-page">

            <div class="card">
                <div class="card-body">

                    <h3 class="text-center mt-0 m-b-15">
                        <a href="{{ url('/') }}" class="logo logo-admin"><img src="{{ $assets }}/images/logo.png" alt="PMA Logo"></a>
                    </h3>

                    @yield('content')

                </div>
            </div>
        </div>

        @include('layouts.public_foot')
        
    </body>
</html>