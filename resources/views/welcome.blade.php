<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BillOrganiser</title>
        <link href="css/app.css" rel="stylesheet" type="text/css">

    </head>
    <body>

        <div class="ui top fixed menu" style="background:none; border:none;">
            <div class="right menu">
                <div class="item">
                    <div class="ui green button">Register</div>
                </div>
                <div class="item">
                    <div class="ui inverted green button">Login</div>
                </div>
            </div>
        </div>

        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    <div class="title"><img src="{{secure_asset('icon.png')}}" style="height: 100px;vertical-align:middle"> Bill<font color="white">Organiser</font></div>
                </div>
            </div>
        </div>
    </body>
</html>
