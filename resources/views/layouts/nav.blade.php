<!--TOP MENU-->
<header id="header">
<div class="ui top fixed menu" style="background:#2ecc71;">
    <div class="ui container">
        <a href="{{ url('/') }}" class="header item"><img class="logo" src="{{url('alt-icon.png')}}">
            &nbsp&nbsp&nbsp&nbsp BillOrganiser
        </a>

        @if (Auth::check())
            <a class="active item" href="{{ url('/dashboard') }}" class="item">Dashboard</a>
            {{--<a href="{{ url('/dashboard') }}" class="item">Statistics</a>--}}

            <div class="ui right simple dropdown item"><i class="user icon"></i>{{ auth()->user()->name }}<i class="dropdown icon"></i>

                <div class="menu">
                    {{--<a class="item" href="#">My Account</a>--}}
                    {{--<div class="divider"></div>--}}
                    {{--<a class="item" href="#">Settings</a>--}}

                    <a class="item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
</header>
