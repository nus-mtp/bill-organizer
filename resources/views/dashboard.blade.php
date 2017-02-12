<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BillOrganiser - Dashboard</title>
        <link href="css/app.css" rel="stylesheet" type="text/css">

    </head>
    <body>
        <!--@section('topmenu')
        <div class="ui top fixed menu" style="background:transparent; border:none;">
            <div class="right menu">
                <div class="item">
                    <div class="ui green button">Register</div>
                </div>
                <div class="item">
                    <a href="{{ url('/dashboard') }}"><div class="ui inverted green button">Login</div>
                </div></a>
            </div>
        </div>-->
        
          <div class="ui fixed pointing menu" style="background:#2ecc71;">
              <div class="ui container">
                  <a href="{{ url('/') }}" class="header item"><img class="logo" src="{{secure_asset('icon.png')}}">
                      &nbsp&nbsp&nbsp&nbsp BillOrganiser
                  </a>
                  
                  <a class="active item" href="{{ url('/dashboard') }}" class="item">Dashboard</a>
                  <a href="{{ url('/dashboard') }}" class="item">Statistics</a>
                  
                  <div class="ui right simple dropdown item">[icon] [username]<i class="dropdown icon"></i>
                      <div class="menu">
                          <a class="item" href="#">My Account</a>
                          <div class="divider"></div>
                          <a class="item" href="#">Settings</a>
                      </div>
                  </div>
              </div>
        </div>
        
        <!--@show-->

        <div class="ui main container" style="background:white; padding:100px 65px 65px 65px;">
            <div class="ui fluid container">
                Hello World
                <div style="height:1500px;"></div>
                <h1>DASHBOARD</h1>
            </div>
        </div>
    </body>
</html>