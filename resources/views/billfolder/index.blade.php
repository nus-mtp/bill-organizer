@extends('layouts.app')

@section('content')
    <!--CONTENT-->
    <div class="ui main container" style="background:white; padding:90px 65px 65px 65px; min-height: 100vh;">

        <div class="ui fluid container">
            <div class="ui grid">
                <div class="sixteen wide column">
                    <div class="ui breadcrumb">
                        <span class="section">Home</span>
                        <i class="right angle icon divider"></i>
                        <span class="section">Dashboard</span>
                        <i class="right angle icon divider"></i>
                        <div class="active section">Sample Organization</div>
                    </div>
                </div>

                <div class="sixteen wide column">
                    <h1>Bills</h1>
                    <!--if no billing organisations in db-->
                    <div class="ui tiny message">
                        <p>There are no records yet - start by adding one below! (ﾉ^ヮ^)ﾉ*:・ﾟ✧</p>
                    </div>
                </div>
                <div class="four wide column">
                    <div class="dotted-container">
                        <button class="circular blue ui icon button" value="showModal" onClick="$('.ui.modal').modal('show');">
                            <i class="icon plus"></i>
                        </button>
                        <p>Add New Record</p>
                    </div>
                </div>
                <div class="four wide column"></div>
                <div class="four wide column"></div>
                <div class="four wide column"></div>
            </div>

            <div class="ui small modal">
                <i class="close icon"></i>
                <div class="header">Add new billing organisation</div>
                <div class="content">
                    <div class="ui fluid icon input">
                        <input type="text" placeholder="Enter billing organisation name">
                    </div>
                </div>
                <div class="actions">
                    <div class="ui button approve green" data-value="yes">Add</div>
                    <div class="ui button cancel" data-value="no">Cancel</div>
                </div>
            </div>
        </div>
    </div>
@endsection