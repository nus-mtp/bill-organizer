<!--TOP MENU-->
<div class="row">
    <div class="ui top borderless stackable menu" style="background:#2ecc71;">
        <div class="ui container">
            <a href="{{ url('/') }}" class="header item"><img class="logo" src="{{url('alt-icon.png')}}">
                &nbsp&nbsp&nbsp&nbsp BillOrganiser
            </a>

            @if (Auth::check())
                <a class="active item" href="{{ url('/dashboard') }}" class="item">Dashboard</a>
                <a href="{{ url('/dashboard') }}" class="item">Statistics</a>

                <div class="ui right simple dropdown item">[icon] {{ auth()->user()->name }}<i class="dropdown icon"></i>
                    <div class="menu">
                        <a class="item" href="#">My Account</a>
                        <div class="divider"></div>
                        <a class="item" href="#">Settings</a>

                        <a class="item logout button">
                            Logout
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

