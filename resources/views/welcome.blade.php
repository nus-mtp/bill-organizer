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
                            <button class="ui green register button" value="showModal">Register</button>
                        </div>
                        <div class="item">
                            <a class="ui inverted green button" href="{{ route('login') }}">Login</a>
                        </div>

                        <div class="ui small register modal">
                          <div class="header">Register</div>
                          <div class="content" style="text-align:left;">
                              <form method="POST" action="{{ route('register') }}"class="ui equal width form" id="registration">
                                {{ csrf_field() }}

                              <!--  <div class="fields"> -->
                                  <div class="field">
                                    <label for="name">Name</label>
                                    <input id="name" type="text" name="name" placeholder="Name" required autofocus>
                                  </div>
                  <!--                <div class="field">
                                    <label>Last Name</label>
                                    <input id="last-name" type="text" name="last-name" placeholder="Last Name">
                                  </div>
                                </div> -->
                                <div class="field">
                                  <label for="email">Email</label>
                                  <input id="email" type="text" name="email" placeholder="Email" required>
                                </div>
                                <div class="field">
                                  <label for="password">Password</label>
                                  <input id="password" type="password" name="password" placeholder="Password" required>
                                </div>
                                <div class="field">
                                  <label for="password-confirm">Password Confirm</label>
                                  <input id="password-confirm" type="password" name="password-confirm" placeholder="Retype Password" required>
                                </div>
                                <div class="action" style="text-align:right;">
                                  <div class="ui primary button">Register</div>
                                </div>
                              </form>
                          </div>
                        </div>

                        <div class="ui small second coupled modal">
                          <div class="header">Registered Successfully</div>
                          <div class="content" style="text-align:left;">
                            <p>You may now proceed to login.</p>
                          </div>
                          <div class="actions">
                            <div class="ui approve button">Close</div>
                          </div>
                        </div>

                        <div class="ui small basic login modal">
                          <div class="header">Login</div>
                          <div class="content" style="text-align:left;">
                            <form class="ui form">
                              <div class="field">
                                <label>Username</label>
                                <input type="text">
                              </div>
                              <div class="field">
                                <label>Password</label>
                                <input type="password">
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
        
                <script src="https://cdnjs.cloudflare.com/ajax/libs/headroom/0.9.3/headroom.min.js"></script>
        @yield('pre-javascript')
            <script src="{{ asset('js/manifest.js') }}"></script>
            <script src="{{ asset('js/vendor.js') }}"></script>
            <script src="{{ asset('js/app.js' ) }}"></script>
        @yield('javascript')
         <!-- Dump all dynamic scripts into template -->
        @stack('module_scripts')

    </body>
</html>
