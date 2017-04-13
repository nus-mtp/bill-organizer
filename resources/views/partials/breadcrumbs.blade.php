<div class="ui breadcrumb">
    <a href="{{route('dashboard')}}">Dashboard</a>
    @if (Route::is('show_record_issuer'))
        <i class="right angle icon divider"></i>
        {{$active_section}}
    @endif
    @if (Route::is('add_template'))
        <i class="right angle icon divider"></i>
        {{$record_issuer}}
        <i class="right angle icon divider"></i>
        {{$active_section}}
    @endif
</div>