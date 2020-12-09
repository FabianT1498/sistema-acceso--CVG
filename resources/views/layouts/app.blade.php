<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'COINTRA') }}</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png" sizes="72x72">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('css/icheck-bootstrap.min.css') }}">
    
    @yield('mascss')

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('css/summernote-bs4.css') }}">
    <!-- Jquery UI CSS-->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div id="app" class="wrapper">
        <main>
            @yield('content')
        </main>
        @include('layouts.footer')
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>

    <!-- Script Generales de la aplicacion -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- JQUERY -->
    <script src="{{ asset('js/jquery.js') }}"></script>
   
    <!-- Bootstrap 4 -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button)
    </script>
    
    <!-- daterangepicker -->
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('js/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('js/jquery.overlayScrollbars.min.js') }}"></script>
 
<!--     <script src="{{ asset('js/dashboard.js') }}"></script>
 -->
    <script type="text/javascript" src="{{ asset('js/jquery.mask.js') }}"></script>

    <script>
        var baseUrl = "{{url('/')}}";
    </script>

    @yield('masjs')

</body>
</html>
