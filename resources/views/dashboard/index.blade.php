@extends('layouts.app')
<!-- we want to have individual page display its title on top of browser -->
@section('title', 'Dashboard')
@section('content')
    <!--CONTENT-->
    <div class="ui main container" style="background:white; min-height: 100vh;">

        <div class="ui fluid container">
            <div class="ui grid">
                <div class="sixteen wide column">
                    {{-- todo: first and second level breadscrump slots--}}
                    @component('partials.breadscrumb')
                    @slot('active_section')
                    @endslot
                    @endcomponent
                </div>

                @if(empty($user_record_issuers))
                    <div class="sixteen wide column">
                        <h1>Billing Organisations</h1>
                        <!--if no billing organisations in db-->
                        <div class="ui tiny message">
                            <p>There are no billing organisations yet - start by adding one below! (ﾉ^ヮ^)ﾉ*:・ﾟ✧</p>
                        </div>
                    </div>
                @endif

                @foreach($user_record_issuers as $record_issuer)
                    @include('partials.billorgEntry')
                @endforeach

                <div class="four wide column">

                    <div class="dotted-container">
                        <button class="circular blue ui icon add-bill-org button" value="showModal">
                            <i class="icon plus"></i>
                        </button>
                        <p>Add New Record Issuer</p>
                    </div>
                </div>
            </div>

            <div class="ui small record-issuer modal">
                <i class="close icon"></i>
                <div class="header">Add new record issuer</div>
                <div class="content">
                    <div class="ui fluid form">
                        <form method="POST" action="{{ url('/dashboard/record_issuers') }}"
                              class="ui form" id="add-record-issuer">
                            {{ csrf_field() }}
                            <div class="ui error message"></div>
                            <div class="field">
                                <label for="name">
                                    Name:
                                </label>
                                <input id="name" type="text" name="name" placeholder="Enter record issuer name">
                            </div>
                            <div class="field">
                                <label for="type">
                                    Type
                                </label>
                                <select name="type" id="type">
                                    @foreach($record_issuer_types as $record_issuer_type)
                                        <option value="{{ $record_issuer_type->id }}">{{ $record_issuer_type->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="actions">
                    <div class="ui button approve green" data-value="yes">Add</div>
                    <div class="ui button black cancel" data-value="no">Cancel</div>
                </div>
            </div>
        </div>
    </div>
@endsection
