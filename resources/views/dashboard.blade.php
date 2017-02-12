<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BillOrganiser - Dashboard</title>
        <link href="css/app.css" rel="stylesheet" type="text/css">

    </head>
    <body>
        
        <!--TOP MENU-->
        
          <div class="ui fixed pointing menu" style="background:#2ecc71;">
              <div class="ui container">
                  <a href="{{ url('/') }}" class="header item"><img class="logo" src="{{secure_asset('alt-icon.png')}}">
                      &nbsp&nbsp&nbsp&nbsp BillOrganiser
                  </a>
                  
                  <a class="active item" href="{{ url('/dashboard') }}" class="item">Dashboard</a>
                  <a href="{{ url('/dashboard') }}" class="item">Statistics</a>
                  
                  <div class="ui right simple dropdown item">[icon] [username]<i class="dropdown icon"></i>
                      <div class="menu">
                          <a class="item" href="#">My Account</a>
                          <div class="divider"></div>
                          <a class="item" href="#">Settings</a>
                          <a class="item" href="#">Logout</a>
                      </div>
                  </div>
              </div>
        </div>
        
        <!--CONTENT-->

        <div class="ui main container" style="background:white; padding:90px 65px 65px 65px; min-height: 100vh;">
            
            <div class="ui fluid container">
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <div class="ui breadcrumb">
                            <a class="section">BillOrganiser</a>
                            <i class="right angle icon divider"></i>
                            <div class="active section">Dashboard</div>
                        </div>
                    </div>
                    
                    <div class="sixteen wide column">
                        <h1>Billing Organisations</h1>
                        <p>There are no billing organisations yet - start by adding one below! (ﾉ^ヮ^)ﾉ*:・ﾟ✧</p>
                    </div>
                    <div class="four wide column">
                        <div class="dotted-container">
                            <button class="circular blue ui icon button">
                                <i class="icon plus"></i>
                            </button>
                            <p>Add New Billing Organisation</p>
                        </div>
                    </div>
                    <div class="four wide column"></div>
                    <div class="four wide column"></div>
                    <div class="four wide column"></div>
</div>
            </div>
        </div>
    </body>
</html>