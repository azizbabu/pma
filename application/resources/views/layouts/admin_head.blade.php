<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<title>
	@hasSection ('title')
        @yield('title') - {{ env('APP_NAME') }}
  	@else
      {{ env('APP_NAME') }}
  	@endif
</title>
<meta content="Admin Dashboard" name="description" />
<meta content="ThemeDesign" name="author" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="shortcut icon" href="{{ $assets }}/images/favicon.ico">

<!--Morris Chart CSS -->
<link rel="stylesheet" href="{{ $assets }}/plugins/morris/morris.css">

<link href="{{ $assets }}/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="{{ $assets }}/css/icons.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ $assets }}/plugins/toaster/jquery.toast.css">
<link rel="stylesheet" href="{{ $assets }}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="{{$assets}}/plugins/chosen/chosen.css">
<link href="{{ $assets }}/css/style.css" rel="stylesheet" type="text/css">
<link href="{{ $assets }}/css/custom.css" rel="stylesheet" type="text/css">

@yield('custom-style')