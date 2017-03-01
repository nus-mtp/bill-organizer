<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
        @section('title')
            {{ config('app.name', 'Laravel') }}
        @show
        </title>

        <link rel="icon" type="image/ico" href="">
        <!-- Local -->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">

        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        @stack('module_styles')<!-- module specific styles -->
        <!-- Scripts -->
        <script>
            window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
        </script>

    </head>
    <body class='ui container'>

        @include('layouts.nav')
        <div class="ui basic padded segment">
            @yield('content')
        </div>
        <div class="footer">
            <!-- <p>&copy; {!! date('Y'); !!} <a href="">xxx</a></p> -->
        </div>

        @yield('pre-javascript')
            <script src="{{ asset('js/manifest.js') }}"></script>
            <script src="{{ asset('js/vendor.js') }}"></script>
            <script src="{{ asset('js/app.js' ) }}"></script>
        @yield('javascript')
         <!-- Dump all dynamic scripts into template -->
        @stack('module_scripts')
    </body>
</html>
