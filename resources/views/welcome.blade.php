<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>BillOrganiser</title>

        <link rel="icon" type="image/ico" href="">
        <!-- Local -->
        <link rel="stylesheet" type="text/css" href="{{ '/css/app.css' }}">

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
    <body class="dark-background">

        <div class="ui top fixed menu" style="background:none; border:none;">
            <div class="right menu">
                <!--should not need this part anymore coz got auto-redirect-->
                @if (Route::has('login'))
                    @if (Auth::check())
                        <div class="item">
                            <a class="ui green button" href="{{ url('/dashboard') }}">Dashboard</a>
                        </div>
                    @else
                        <div class="item">
                            <button class="ui green register button">Register</button>
                        </div>
                        <div class="item">
                            <button class="ui inverted green login button">Login</button>
                        </div>

                        <div class="ui small register modal">
                          <div class="header">Register</div>
                          <div class="content" style="text-align:left;">
                              <form class="ui register form" id="register" role="form" method="POST" action="{{ route('register') }}" >
                                {{ csrf_field() }}
                                  <div class="ui tiny error message"></div>
                                <div class="field">
                                  <label for="name">Name <atn>*</atn></label>
                                  <input id="name" type="text" name="name" placeholder="Name" autofocus>
                                </div>
                                <div class="field">
                                  <label for="email">Email <atn>*</atn></label>
                                  <input id="email" type="text" name="email" placeholder="Email">
                                </div>
                                <div class="field">
                                  <label for="password">Password <atn>*</atn></label>
                                  <input id="password" type="password" name="password" placeholder="Password">
                                </div>
                                <div class="field">
                                  <label for="password-confirm">Password Confirm <atn>*</atn></label>
                                  <input id="password-confirm" type="password" name="password_confirmation" placeholder="Retype Password">
                                </div>
                                  <tnc><atn>*</atn> <i>Indicates required field</i><br><br></tnc>
                                <div class="actions" style="text-align:right;">
                                    <button class="ui approve green button" type="submit">Register</button>
                                    <div class="ui button black cancel" data-value="no" onclick="$('form').form('reset'); $('.form .message').html('');">Cancel</div>
                                </div>
                            </form>
                          </div>
                        </div>

                        <div class="ui small login modal">
                          <div class="header">Login</div>
                          <div class="content">
                            <form class="ui login form" id="login" role="form" method="POST" action="{{ route('login') }}">
                              {{ csrf_field() }}
                                <div class="ui tiny error message"></div>
                              <div class="field">
                                <label for="email">Email Address</label>
                                <input id="email" type="text" name="email">
                              </div>
                              <div class="field">
                                <label for="password">Password</label>
                                <input id="login_password" type="password" name="password">
                              </div>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                </label>
                              </div>
                              <a class="btn btn-link" style="text-align:right;" href="{{ route('password.request') }}"><tnc>Forgot Your Password?</tnc></a>

                              <div class="actions" style="text-align:right;">
                                    <button class="ui approve green button" type="submit">Login</button>
                                    <div class="ui button black cancel" data-value="no" onclick="$('form').form('reset'); $('.form .message').html('');">Cancel</div>
                              </div>
                            </form>
                          </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <div class="flex-center position-ref full-height">
            <div class="ui center aligned container">
                <div class="title m-b-md">
                    <div class="title"><img src="{{url('icon.png')}}" style="height: 100px;vertical-align:middle"> Bill<font color="white">Organiser</font></div>
                </div>
            </div>
        </div>

        @include('layouts.scripts')

    </body>
</html>
