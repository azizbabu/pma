<!DOCTYPE html>
<html>
    <head>
        @include('layouts.admin_head')
    </head>


    <body class="fixed-left">

        <!-- Loader -->
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>

        <!-- BEGAIN AJAXLOADER -->
        <div id="ajaxloader" class="hide">
            <div id="status">&nbsp;</div>
        </div>
        <!-- END AJAXLOADER -->

        <!-- Begin page -->
        <div id="wrapper">
            
            @include('layouts.admin_left_sidebar')
            
            <!-- Start right Content here -->

            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    
                    @include('layouts.admin_top_bar')
                    
                    <div class="page-content-wrapper ">

                        <div class="container-fluid">

                            @yield('content')

                        </div><!-- container -->


                    </div> <!-- Page content Wrapper -->

                </div> <!-- content -->

                @include('global_modal')

                <footer class="footer">
                    © {{ date('Y') }} {{ env('APP_COMPANY_NAME') }} - Designed &amp; Developed <i class="mdi mdi-heart text-danger"></i> by {{ env('APP_CREDIT') }}.
                </footer>

            </div>
            <!-- End Right content here -->

        </div>
        <!-- END wrapper -->


        @include('layouts.admin_foot')

    </body>
</html>