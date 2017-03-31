<div class="ui breadcrumb">
    <span class="section">
        Home
    </span>
    <i class="right angle icon divider"></i>
    <a href="{{route('dashboard')}}">Dashboard</a>
    @if (Route::is('show_record_issuer'))
        <i class="right angle icon divider"></i>
        {{$active_section}}
    @endif
</div>