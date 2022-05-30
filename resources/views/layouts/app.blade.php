<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{url('assets/css/bootstrap.css')}}">

    <link rel="stylesheet" href="{{url('assets/vendors/iconly/bold.css')}}">

    <link rel="stylesheet" href="{{url('assets/vendors/perfect-scrollbar/perfect-scrollbar.css')}}">
    <link rel="stylesheet" href="{{url('assets/vendors/bootstrap-icons/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{url('assets/css/app.css')}}">
    <link rel="shortcut icon" href="{{url('assets/images/favicon.svg')}}" type="image/x-icon">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include('includes.sidebar')
        @yield('content')
    </div>
    <script src="{{url('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{url('assets/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{url('assets/vendors/apexcharts/apexcharts.js')}}"></script>
    <script src="{{url('assets/js/pages/dashboard.js')}}"></script>

    <script src="{{url('assets/js/main.js')}}"></script>
</body>
</html>
