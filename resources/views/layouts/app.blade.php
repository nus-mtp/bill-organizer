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

        <!-- TODO: extract to css file -->
        <style>
            form > .field {
                text-align: left;
            }

            .field {
                margin-bottom: 1.2em;
            }

            .field > label {
                font-weight: bold;
            }

            .field > input, select {
                display: block;
            }

            tfoot tr td {
                border-top: 1px solid rgba(34, 36, 38, 0.1) !important;
            }

            a .icon {
                margin-right: auto !important;
                margin-left: auto !important;
            }

            a .file.icon {
                color :#4183C4; /* normal link color */
            }

            a .download.icon {
                color: #6435c9;
            }

            a .remove.icon {
                color: #db2828;
            }

            /**
             * Styles for input["month"] (non-standard) copied from semantic.css
             * TODO: move this somewhere else
             */
            .ui.form input[type="month"] {
                /* Standard Input */
                width: 100%;
                vertical-align: top;

                font-family: 'Lato', 'Helvetica Neue', Arial, Helvetica, sans-serif;
                margin: 0em;
                outline: none;
                -webkit-appearance: none;
                tap-highlight-color: rgba(255, 255, 255, 0);
                line-height: 1.2142em;
                padding: 0.67861429em 1em;
                font-size: 1em;
                background: #FFFFFF;
                border: 1px solid rgba(34, 36, 38, 0.15);
                color: rgba(0, 0, 0, 0.87);
                border-radius: 0.28571429rem;
                box-shadow: 0em 0em 0em 0em transparent inset;
                -webkit-transition: color 0.1s ease, border-color 0.1s ease;
                transition: color 0.1s ease, border-color 0.1s ease;
            }

            .ui.form input[type="month"]:focus {
                color: rgba(0, 0, 0, 0.95);
                border-color: #85B7D9;
                border-radius: 0.28571429rem;
                background: #FFFFFF;
                box-shadow: 0px 0em 0em 0em rgba(34, 36, 38, 0.35) inset;
            }

            .ui.form .fields.error .field input[type="month"],
            .ui.form .field.error input[type="month"] {
                background: #FFF6F6;
                border-color: #E0B4B4;
                color: #9F3A38;
                box-shadow: none;
            }

            .ui.form .field.error input[type="month"]:focus {
                background: #FFF6F6;
                border-color: #E0B4B4;
                color: #9F3A38;
                -webkit-appearance: none;
                box-shadow: none;
            }

            .ui.inverted.form input[type="month"] {
                background: #FFFFFF;
                border-color: rgba(255, 255, 255, 0.1);
                color: rgba(0, 0, 0, 0.87);
                box-shadow: none;
            }
        </style>
    </head>
    <body>
        <div class="ui basic segment">
            @include('layouts.nav')
        </div>
            <div class="ui padded segment">
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
