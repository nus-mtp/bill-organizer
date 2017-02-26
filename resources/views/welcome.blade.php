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
                @if (Route::has('login'))
                    @if (Auth::check())
                        <div class="item">
                            <a class="ui green button" href="{{ url('/dashboard') }}">Dashboard</a>
                        </div>
                    @else
                        <div class="item">
                            <a class="ui green button" href="{{ route('register') }}">Register</a>
                        </div>
                        <div class="item">
                            <a class="ui inverted green button" href="{{ route('login') }}">Login</a>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    <div class="title"><img src="{{url('icon.png')}}" style="height: 100px;vertical-align:middle"> Bill<font color="white">Organiser</font></div>
                </div>
            </div>
        </div>
    </body>
</html>
