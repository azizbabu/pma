<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>
      @hasSection ('title')
          @yield('title') - {{env('APP_NAME')}}
      @else
          {{ env('APP_NAME') }}
      @endif
    </title>
    <link media="all" href="{{ $assets }}/css/bootstrap.min.css" rel="stylesheet">
    <link media="all" href="{{ $assets }}/css/icons.css" rel="stylesheet">
    <link media="all" href="{{ $assets }}/css/custom.css" rel="stylesheet">

    @yield('custom-style')

  </head>
  <body>
    <div class="container-fluid">
      @yield('content')
    </div>
    <script>
        window.print();
    </script>
  </body>
</html>
