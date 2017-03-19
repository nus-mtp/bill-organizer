@extends('layouts.app')
<!-- we want to have individual page display its title on top of browser -->
@section('title', 'Dashboard') @section('content')
<!--CONTENT-->
<div class="ui main container" style="background:white; min-height: 100vh;">
    <div class="ui fluid container">
        <div class="ui grid">
            <div class="sixteen wide column">
                {{-- todo: first and second level breadscrump slots--}} @component('partials.breadscrumb') @slot('active_section') @endslot @endcomponent
            </div>

            @if(empty($record_issuers))
            <div class="sixteen wide column">
                <h1>Billing Organisations</h1>
                <!--if no billing organisations in db-->
                <div class="ui tiny message">
                    <p>There are no billing organisations yet - start by adding one below! (ﾉ^ヮ^)ﾉ*:・ﾟ✧</p>
                </div>

                @endif

                <div class="ui four doubling cards" style="width:100%;">
                    @foreach($record_issuers as $record_issuer) @include('partials.billorgEntry') @endforeach

                    <div class="add-bill-org card">
                        <div class="content">
                            <center>
                                <p>Add New Record Issuer</p>
                            </center>
                        </div>
                        <button class="fluid blue ui icon add-bill-org button">
                            <i class="icon plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ui small record-issuer modal">
        <i class="close icon"></i>
        <div class="header">Add new record issuer</div>
        <div class="content">
            <div class="ui fluid record-issuer form">
                <form method="POST" action="{{ url('/dashboard/record_issuers') }}" class="ui form" id="add-record-issuer">
                    {{ csrf_field() }}
                    <div class="ui tiny error message"></div>
                    <div class="field">
                        <label for="name">Name <atn>*</atn></label>
                        <input id="name" type="text" name="name" placeholder="Enter record issuer name">
                    </div>
                    <div class="field">
                        <label for="type">Type</label>
                        <select name="type" id="type">
                                    @foreach($record_issuer_types as $record_issuer_type)
                                        <option value="{{ $record_issuer_type->id }}">{{ $record_issuer_type->type }}</option>
                                    @endforeach
                                </select>
                    </div>
                    <tnc>
                        <atn>*</atn> <i>Indicates required field</i></tnc>
                </form>
            </div>
        </div>
        <div class="actions">
            <div class="ui button approve green" data-value="yes">Add</div>
            <div class="ui button black cancel" data-value="no" onclick="$('form').form('reset'); $('.form .message').html('');">Cancel</div>
        </div>
    </div>

    <!-- nope it still doesnt work nvm
    <div class="ui small record-issuer-del-cfm modal">
        <i class="close icon"></i>
        <div class="content">
            <p>Are you sure you want to delete this record issuer?</p>
        </div>
        <div class="actions">
            <form method="POST" action="{{ url('/dashboard/record_issuers/' . $record_issuer->id) }}">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="ui inverted red button">
                    Delete
                </button>
                <div class="ui button black cancel">Cancel</div>
            </form>
        </div>
    </div>-->
</div>

@endsection
