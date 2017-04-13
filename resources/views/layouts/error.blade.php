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
            {{ config('app.name', 'Owlganizer') }}
        @show
    </title>

    <link rel="icon" type="image/ico" href="">
    <!-- Local -->
    <link rel="stylesheet" type="text/css" href="/css/app.css">

    <style>
        body > .grid {
            height: 100%;
        }

        body > .grid > .column {
            height: 96%;
        }

        body > .grid > .column > .segment {
            height: 100%;
        }

        img#error-hero {
            max-height: 70%;
            min-height: 60%;
        }

        h1.ui.header {
            font-size: 3.5rem;
        }

        h2.ui.header {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <div class="ui middle aligned center aligned grid">
        <div class="ten wide column">
            <div class="ui stacked segment">
                <h1 class="ui header">@yield('error_code')</h1>
                <h2 class="ui header">@yield('error_message')</h2>
                @yield('error_instruction')
                @yield('error_image')
            </div>
        </div>
    </div>
</body>
</html>
