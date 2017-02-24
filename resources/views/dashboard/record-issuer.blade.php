@extends('layouts.app')

@section('content')
    <!--CONTENT-->
    <div class="ui main container" style="background:white; padding:90px 65px 65px 65px; min-height: 100vh;">

        <div class="ui fluid container">
            <div class="ui grid">
                <div class="sixteen wide column">
                    <div class="ui breadcrumb">
                        <!-- TODO: Extract breadcrumbs and add links-->
                        <span class="section">Home</span>
                        <i class="right angle icon divider"></i>
                        <span class="active">Dashboard</span>
                        <i class="right angle icon divider"></i>
                        <span class="active section">{{ $record_issuer->name }}</span>
                    </div>
                </div>

                @if(count($records) === 0)
                    <div class="sixteen wide column">
                        <h1>{{ $record_issuer->name }}</h1>
                        <div class="ui tiny message">
                            <p>There isn't any record yet - start by adding one below! (ﾉ^ヮ^)ﾉ*:・ﾟ✧</p>
                        </div>
                        <div class="dotted-container">
                            <button class="circular blue ui icon button" value="showModal"
                                    onClick="$('.ui.modal.add-record').modal({onApprove: function() {
                                            $('form#add-record').submit();
                                        }}).modal('show');">
                                <i class="icon plus"></i>
                            </button>
                            <span>Add new record</span>
                        </div>
                    </div>

                @endif
            </div>

            @if(count($records) > 0)
                <table class="ui celled striped table">
                    <thead>
                    <tr>
                        <th>Issue date</th>
                        <th>Period</th>
                        @if($type === 'billing organization')
                            <th>Due date</th>
                        @endif
                        <th>{{ $amount_field_name }}</th>
                        <th></th>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>{{ $record->issue_date }}</td>
                            <td>{{ $record->period }}</td>
                            @if($type === 'billing organization')
                                <td>{{ $record->due_date }}</td>
                            @endif
                            <td>${{ $record->amount }}</td>
                            <td style="text-align: right; width: 1%">
                                <div class="ui small basic icon buttons">
                                    <button class="ui button">
                                        <a href="{{ route('show_record_file', $record) }}">
                                            <i class="file icon"></i>
                                        </a>
                                    </button>
                                    <button class="ui button">
                                        <a href="{{ route('download_record_file', $record) }}">
                                            <i class="download icon"></i>
                                        </a>
                                    </button>
                                    <button class="ui button">
                                        <i class="write icon"></i>
                                    </button>
                                    <button class="ui button">
                                        <form method="POST" action="{{ route('delete_record_file', $record) }}"
                                              style="display: none;" id="delete-record">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                        <a href="{{ route('delete_record_file', $record) }}" onclick="event.preventDefault();
                                            document.getElementById('delete-record').submit()">
                                            <i class="remove icon"></i>
                                        </a>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: center">
                                <button class="circular blue ui icon button" value="showModal"
                                        onClick="$('.ui.modal.add-record').modal({onApprove: function() {
                                                    $('form#add-record').submit();
                                                }}).modal('show');">
                                    <i class="icon plus"></i>
                                </button>
                                <span>Add new record</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            @endif

            <div class="ui small add-record modal">
                <i class="close icon"></i>
                <div class="header">Add new record</div>
                <div class="content">
                    <div class="ui fluid input">
                        <form method="POST" action="{{ route('records', $record_issuer) }}"
                              class="ui form" enctype="multipart/form-data" id="add-record">
                            <!-- TODO: customize form based on type -->
                            <!-- TODO: research on semantic UI calendar -->
                            {{ csrf_field() }}
                            <div class="field">
                                <label for="record">Upload the record:</label>
                                <input type="file" name="record" id="record">
                            </div>

                            <div class="field">
                                <label for="issue_date">Issue date:</label>
                                <input type="date" name="issue_date" id="issue_date" placeholder="Issue date">
                            </div>

                            <div class="field">
                                <label for="period">Record period:</label>
                                <input type="month" name="period" id="period" placeholder="Record period">
                            </div>

                            @if($type === 'billing organization')
                                <div class="field">
                                    <label for="due_date">Due date:</label>
                                    <input type="date" name="due_date" id="due_date" placeholder="Due date">
                                </div>
                            @endif

                            <div class="field">
                                <!-- TODO: customize based on type -->
                                <label for="amount">{{ $amount_field_name }}:</label>
                                <input type="number" name="amount" id="amount" placeholder="{{ $amount_field_name }}">
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