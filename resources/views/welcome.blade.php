<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BillOrganiser</title>
        <link href="css/app.css" rel="stylesheet" type="text/css">

    </head>
    <body class="dark-background">

        <div class="ui top fixed menu" style="background:none; border:none;">
            <div class="right menu">
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
                              <form class="ui equal width form">
                                <div class="fields">
                                  <div class="field">
                                    <label>First Name</label>
                                    <input type="text" placeholder="First Name">
                                  </div>
                                  <div class="field">
                                    <label>Last Name</label>
                                    <input type="text" placeholder="Last Name">
                                  </div>
                                </div>
                                <div class="field">
                                  <label>Email</label>
                                  <input type="text" placeholder="Email">
                                </div>
                                <div class="field">
                                  <label>Password</label>
                                  <input type="password" placeholder="Password">
                                </div>
                                <div class="field">
                                  <label>Password Confirm</label>
                                  <input type="password" placeholder="Retype Password">
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
    </body>
</html>
