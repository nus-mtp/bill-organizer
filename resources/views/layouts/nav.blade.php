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