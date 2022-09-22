<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <title>BI - EXPLORER</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- CSRF Token -->
  <meta name="_token" content="{{ csrf_token() }}">
  
  <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

  <!-- plugin css -->
  <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
  <!-- end plugin css -->

  @stack('plugin-styles')
  <style>
    @media print {
      .no-print {display: none;}
    }
  </style>
  <!-- common css -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
  <!-- end common css -->

  @stack('style')
</head>
<body data-base-url="{{url('/')}}">

  <script src="{{ asset('assets/js/spinner.js') }}"></script>

  <div class="main-wrapper" id="app">
 
    @include('layout.relatorio-admin.sidebar')
    <div class="page-wrapper">
      <div class="page-content-relatorio">
      <meta name="csrf-token" content="{{ Session::token() }}"> 
        @yield('content')
      </div>
    <!--  @include('layout.administradores.footer') -->
    </div>
  </div>

    <!-- base js -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->

    <!-- common js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/temaAdmin.js') }}"></script>
    <script src="{{ asset('assets/js/operacoes-relatorio.js') }}"></script>
    <!-- end common js -->

    @stack('custom-scripts')
    @include('sweetalert::alert')
</body>
</html>