<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
        <i class="ion-close"></i>
    </button>

    <!-- LOGO -->
    <div class="topbar-left">
        <div class="text-center">
            <!-- <a href="{{ url('/home') }}" class="logo">{{ $app_name }}</a> -->
            <a href="{{ url('/home') }}" class="logo"><img src="{{ $assets }}/images/logo.png" alt="logo"></a>
        </div>
    </div>

    <div class="sidebar-inner slimscrollleft">

        
        <div class="user-details">
            <div class="text-center d-none">
                <img src="{{ $assets }}/images/users/avatar-6.jpg" alt="" class="rounded-circle">
            </div>
            <div class="user-info">
                <h4 class="font-16">{{ Auth::user()->name }} <small>({{ Auth::user()->role->name }})</small></h4>
                <span class="text-muted user-status d-none"><i class="fa fa-dot-circle-o text-success"></i> Online</span>
            </div>
        </div>

        <div id="sidebar-menu">
            <ul>
                <li class="menu-title d-none">Main</li>

                <li>
                    <a href="{{ url('home') }}" class="waves-effect">
                        <i class="ti-home"></i>
                        <span> Dashboard <span class="badge badge-primary pull-right d-none">3</span></span>
                    </a>
                </li>

                {!! getMenus() !!}

            </ul>
        </div>
        <div class="clearfix"></div>
    </div> <!-- end sidebarinner -->
</div>
<!-- Left Sidebar End -->