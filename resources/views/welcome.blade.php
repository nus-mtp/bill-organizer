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

                        <div class="ui small register modal" id="fuck">
                          <div class="header">Register</div>
                          <div class="content" style="text-align:left;">
                              <form class="ui register form" id="register" role="form" method="POST" action="{{ route('register') }}" >
                                {{ csrf_field() }}
                                  
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                  
                                @if ($errors->has('email'))
                                    <span class="help-block" id="emailerror">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                  
                                <div class="inline field">
                                  <label for="name">Name <span class="atn">*</span></label>
                                  <input id="name" type="text" name="name" placeholder="Name" style="margin-left: 5.5em;">
                                </div>
                                <div class="inline field">
                                  <label for="email">Email <span class="atn">*</span></label>
                                  <input id="email" type="text" name="email" placeholder="Email" style="margin-left: 5.7em;">
                                </div>
                                <div class="inline field">
                                  <label for="password">Password <span class="atn">*</span></label>
                                  <input id="password" type="password" name="password" placeholder="Password" style="margin-left: 3.9em;">
                                </div>
                                <div class="inline field">
                                  <label for="password-confirm">Password Confirm <span class="atn">*</span></label>
                                  <input id="password-confirm" type="password" name="password_confirmation" placeholder="Retype Password">
                                </div>
                                  <span class="tnc"><span class="atn">*</span> <i>Indicates required field</i><br><br></span>
                                <div class="actions" style="text-align:right;">
                                    <button class="ui approve green button" type="submit">Register</button>
                                    <div class="ui black clear cancel button" data-value="no">Cancel</div>
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
                              <div class="inline field">
                                <label for="email">Email Address <span class="atn">*</span></label>
                                <input id="email" type="text" name="email">
                              </div>
                              <div class="inline field">
                                  <label for="password">Password <span class="atn">*</span></label>
                                <input id="login_password" type="password" name="password" style="margin-left: 2.1em;">
                              </div>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                </label>
                              </div>
                              <a class="btn btn-link" style="text-align:right;" href="{{ route('password.request') }}"><tnc>Forgot Your Password?</tnc></a>

                              <div class="actions" style="text-align:right;">
                                    <button class="ui approve green button" type="submit">Login</button>
                                    <div class="ui black clear cancel button" data-value="no">Cancel</div>
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
        <script type="text/javascript">
          $(function(){
            onLandingPageLoad();
              if(document.getElementById('emailerror')){
            showModalwithErrors();        
        }
          })
          
          function showModalwithErrors(){
              $('#fuck').modal('show');
          }
        </script>

    </body>
</html>
