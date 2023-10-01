<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <style>
        #mainnotification{
            max-height: 500px; /* set a custom maximum height */
            overflow-y: auto; /* add vertical scrollbar when necessary */
        }
    </style>

    @yield('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-closed sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('dist/img/logo1.png') }}" alt="MAR EXPRESS" height="60"
                width="250">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('home') }}" class="nav-link">{{ __('home') }}</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">{{ __('contact') }}</a>
                </li>
                <li class="nav-item d-flex">
                    @if (App::getLocale() == 'en')
                    <a class="nav-link " href="{{ asset('lang/set/ar') }}"><b>ar</b></a>
                    @else
                    <a class="nav-link " href="{{ asset('lang/set/en') }}"><b>en</b></a>
                    @endif
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    {{-- <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{ asset('dist/img/user1-128x128.jpg') }}" alt="User Avatar"
                    class="img-size-50 mr-3 img-circle">
                    <div class="media-body">
                        <h3 class="dropdown-item-title">
                            Brad Diesel
                            <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                        </h3>
                        <p class="text-sm">Call me whenever you can...</p>
                        <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                    </div>
    </div>
    <!-- Message End -->
    </a>
    <div class="dropdown-divider"></div>
    <a href="#" class="dropdown-item">
        <!-- Message Start -->
        <div class="media">
            <img src="{{ asset('dist/img/user8-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
            <div class="media-body">
                <h3 class="dropdown-item-title">
                    John Pierce
                    <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
        </div>
        <!-- Message End -->
    </a>
    <div class="dropdown-divider"></div>
    <a href="#" class="dropdown-item">
        <!-- Message Start -->
        <div class="media">
            <img src="{{ asset('dist/img/user3-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
            <div class="media-body">
                <h3 class="dropdown-item-title">
                    Nora Silvester
                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
        </div>
        <!-- Message End -->
    </a>
    <div class="dropdown-divider"></div>
    <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
    </div> --}}
    </li>
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" onclick="read()">
            <i class="far fa-bell"></i>
            <span id="countermain" class="badge badge-warning navbar-badge"></span>
        </a>
        <div id="mainnotification" class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span id="counterin" class="dropdown-item dropdown-header"></span>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
        </a>
    </li>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    <li class="nav-item">
        <a class="nav-link bg-danger" data-widget="control-sidebar" data-controlsidebar-slide="true" href="href=" {{
            route('logout') }}" role="button" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>
    </li>
    </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->

        <img src="{{ asset('dist/img/logo1.png') }}" alt="MAR Express" id="img">



        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                        alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                        aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                    <li class="nav-item">
                        <a href="#" class="nav-link
                            @if (session()->get('active')=='agent')
                            {{ 'active' }}
                            @endif
                            ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-bag" viewBox="0 0 16 16">
                                <path
                                    d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z" />
                            </svg>
                            <p>{{ __("orders") }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('agent') }}" class="nav-link
                                    @if (session()->get('active') == 'agent') {{ 'active' }} @endif
                                    ">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __("orders_all") }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link
                            @if (session()->get('active') == 'orders_history')
                            {{ 'active' }}
                            @elseif (session()->get('active') == 'history_arcieve')
                            {{ 'active' }}
                            @endif
                            ">
                            <i class="nav-icon fas fa-edit"></i>
                            <p>{{ __('history_archieve') }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('orders_history') }}" class="nav-link
                                    @if (session()->get('active') == 'orders_history') {{ 'active' }} @endif
                                    ">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('orders_history') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('history_arcieve') }}" class="nav-link
                                    @if (session()->get('active') == 'history_arcieve') {{ 'active' }} @endif
                                    ">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('orders_archieve') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>@yield('title')</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">@yield('title')</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        @yield('content')
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2023 <a href="https://zenonsoftware.com">ZenonSoftware</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.2
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js')}}"></script>
    @yield('js')
    @include('layouts.mainjs')
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });
var counter = 0;
setInterval(function() {
    $.ajax({
        type: "GET",
        url: 'https://spear.zenonsoftware.com/notifications',
        dataType: "json",
        success: function(data) {
            counter = data.unread;
            document.getElementById('countermain').textContent = counter;

            data.notifications.forEach(function(notifiy) {
                var notificationId = 'id-' + notifiy.id;
                var existingNotification = document.querySelector('#mainnotification #' + notificationId);

                if (existingNotification) {
                    // Notification exists, update its content
                    existingNotification.innerHTML = '<i class="fas fa-envelope mr-2"></i>' + notifiy.title + '<br>' + notifiy.data + '<span class="float-right text-muted text-sm">' + notifiy.created_at_date + '<br>' + notifiy.created_at_time + '</span>';
                } else {
                    // Notification doesn't exist, add it
                    var newNotification = document.createElement('a');
                    newNotification.href = '#';
                    newNotification.id = notificationId;
                    newNotification.className = 'dropdown-item';
                    newNotification.innerHTML = '<i class="fas fa-envelope mr-2"></i>' + notifiy.title + '<br>' + notifiy.data + '<span class="float-right text-muted text-sm">' + notifiy.created_at_date + '<br>' + notifiy.created_at_time + '</span>';

                    document.getElementById('mainnotification').appendChild(newNotification);
                }
            });

            // Check if any notifications were deleted
            var notificationIds = data.notifications.map(function(notifiy) {
                return 'id-' + notifiy.id;
            });

            var existingNotifications = document.querySelectorAll('#mainnotification a.dropdown-item');
            existingNotifications.forEach(function(notification) {
                var notificationId = notification.id;

                if (!notificationIds.includes(notificationId)) {
                    // Notification was deleted, remove it
                    notification.remove();
                }
            });
        }
    });
}, 5000);
var channel = pusher.subscribe('notifications');
    channel.bind('OrderUpdate', function(data) {

        var notification = '<div class="dropdown-divider"></div><a href="#" id="id-' + data.id + '" class="dropdown-item"><i class="fas fa-envelope mr-2"></i>' + data.title + '<br>' + data.data + '<span class="float-right text-muted text-sm">' + data.created_at_date + '<br>' + data.created_at_time + '</span></a>';
        document.getElementById('mainnotification').innerHTML += notification;
        counter = counter + 1;
        document.getElementById('countermain').textContent = counter;
        document.getElementById('counterin').textContent = counter + ' Notifications';
        var body = data.data;
        icon = 'https://homepages.cae.wisc.edu/~ece533/images/airplane.png';
        if ("Notification" in window) {
        // Request permission to display notifications
        Notification.requestPermission().then(function (result) {
        console.log("Notification permission status:", result);
        });

    // Create and show a notification
        var notification = new Notification(data.title);
        }

        // You can update your UI here with the new notification data.
    });
    function read() {
        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$.ajax({
    url: 'https://spear.zenonsoftware.com/notifications/read',
    type: 'POST',
    success: function(response) {
        counter = 0;
        document.getElementById('countermain').textContent = counter;
    },
    error: function(xhr, textStatus, errorThrown) {
        console.log(errorThrown);
    }
});
    }
    </script>
</body>

</html>
