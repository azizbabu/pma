<!-- jQuery  -->
<script src="{{ $assets }}/js/jquery.min.js"></script>
<script src="{{ $assets }}/js/popper.min.js"></script>
<script src="{{ $assets }}/js/bootstrap.min.js"></script>
<script src="{{ $assets }}/js/modernizr.min.js"></script>
<script src="{{ $assets }}/js/jquery.slimscroll.js"></script>
<script src="{{$assets}}/plugins/toaster/jquery.toast.js"></script>
<script src="{{$assets}}/plugins/chosen/chosen.jquery.min.js"></script>
<script src="{{$assets}}/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<!-- App js -->
<script src="{{ $assets }}/js/app.js"></script>
<script src="{{ $assets }}/js/custom.js"></script>

@yield('custom-script')

@if(session()->has('toast'))
    
    @php
        $toast = session()->get('toast');
        $message = $toast['message'];
        $type = $toast['type'];
    @endphp

    <script>
        toastMsg("{!! $message !!}","{{ $type }}");
    </script>
@endif