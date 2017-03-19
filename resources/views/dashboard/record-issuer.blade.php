@extends('layouts.app')
<!-- page specific styles -->
@push('module_styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.semanticui.min.css">
@endpush
<!-- page specific styles -->

@section('content')
    <!--CONTENT-->
    <div class="ui container" style="background:white; min-height: 100vh;">
        <div class="ui fluid container">

            <div class="ui equal width grid">
                <div class="column">

                    @component('partials.breadscrumb')
                    @slot('active_section')
                    {{ $record_issuer->name }}
                    @endslot
                    @endcomponent

                    <button class="ui circular tiny right floated icon button" id="statsbutton" onclick="togglestats();"><i class="bar chart icon"></i></button>

                <div class="ui basic segment">
                @if(empty($records))
                    <h1>{{ $record_issuer->name }}</h1>
                    <div class="ui tiny message">
                        <p>There isn't any record yet - start by adding one below! (ﾉ^ヮ^)ﾉ*:・ﾟ✧</p>
                    </div>
                    <div class="dotted-container">
                        <button class="ui circular blue add-record icon button" value="showModal">
                        <i class="icon plus"></i>
                        </button>
                        <span>Add new record</span>
                    </div>
                @endif

                @if(!empty($records))
                    <div class="ui right floated blue labeled add-record button" style="margin-top:13px;">
                        <div class="ui blue icon button">
                            <i class="add icon"></i>
                        </div>
                        <a class="ui basic blue label">Add new record</a>
                    </div>
                    <h1>{{ $record_issuer->name }}</h1>
                    <table class="ui green celled striped datatable table">

                        <thead>
                            <tr>
                                <th>Issue date</th>
                                <th>Period</th>
                                @if($type === 'billing organization')
                                    <th>Due date</th>
                                @endif
                                <th>{{ $amount_field_name }}</th>
                                <th><!-- dummy th for action buttons--></th>
                            </tr>
                        </thead>
                    <tbody>

                    @foreach($records as $record)
                        <tr>
                            <td>{{ $record->issue_date->toFormattedDateString() }}</td>
                            <td>{{ $record->period->format('F Y') }}</td>
                            @if($type === 'billing organization')
                                <td>{{ $record->due_date->toFormattedDateString() }}</td>
                            @endif
                            <td>${{ $record->amount }}</td>
                            <td style="text-align: right; width: 1%">
                                <div class="ui small basic icon buttons">
                                    <a href="{{ route('show_record_file', $record) }}" class="ui button">
                                        <i class="file icon"></i>
                                    </a>
                                    <a href="{{ route('download_record_file', $record) }}" class="ui button">
                                        <i class="download icon"></i>
                                    </a>
                                    <a href="{{ route('delete_record_file', $record) }}" onclick="event.preventDefault();
                                            document.getElementById('delete-record').submit()" class="ui button">
                                        <form method="POST" action="{{ route('show_record_file', $record) }}"
                                              style="display: none;" id="delete-record">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>

                                        <i class="red trash icon"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="5" class="bordered centere aligned">
                                <!--footer content-->
                            </td>
                        </tr>
                    </tfoot>

                </table>
            @endif

            <div class="ui small add-record modal">
                <i class="close icon"></i>
                <div class="header">Add new record</div>

                <!-- modal content -->
                <div class="content">
                    <div class="ui fluid add-record form">
                        <form method="POST" action="{{ route('records', $record_issuer) }}"
                              class="ui form" enctype="multipart/form-data" id="add-record">
                            <!-- TODO: customize form based on type -->
                            <!-- TODO: research on semantic UI calendar -->
                            {{ csrf_field() }}
                            <div class="ui tiny error message"></div>
                            <div class="field">
                                <label for="record">Upload the record <atn>*</atn></label>
                                <input type="file" name="record" id="record" accept=".pdf, application/pdf">
                            </div>

                            <div class="field">
                                <label for="issue_date">Issue date <atn>*</atn></label>
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
                                <label for="amount">{{ $amount_field_name }} <atn>*</atn></label>
                                <input type="number" name="amount" id="amount" placeholder="{{ $amount_field_name }}">
                            </div>
                            <tnc><atn>*</atn> <i>Indicates required field</i></tnc>
                        </form>
                    </div><!-- end ui fluid input -->
                </div><!-- end modal content -->

                <div class="actions">
                    <div class="ui button approve green" data-value="yes">Add</div>
                    <div class="ui button black cancel" data-value="no" onclick="$('form').form('reset'); $('.form .message').html('');">Cancel</div>
                </div>

            </div><!-- modal end -->
        </div><!-- basic segment end -->
    </div><!-- column end -->
                <!--SIDEBAR-->
                <div class="four wide column" id="stats" style="height: 100vh; border-left:1px #ccc solid; display:block; text-align:center;">
                    <h4>Statistics for this year</h4>
                    <div class="ui selection dropdown">
                        <input type="hidden" name="filter">
                        <i class="dropdown icon"></i>
                        <div class="default text">Select time period</div>
                        <div class="menu">
                            <div class="item" data-value="0">This Month</div>
                            <div class="item" data-value="1">past 6 months</div>
                            <div class="item" data-value="2">This year</div>
                            <div class="item" data-value="3">Past 2 years</div>
                            <div class="item" data-value="4">All of time</div>
                            <div class="item" data-value="5">Pre Big Bang</div>
                        </div>
                    </div>

                    <div class="ui statistics">

                        <div class="red statistic">
                            <div class="value">0</div>
                            <div class="label">Bills</div>
                        </div>

                        <div class="red statistic">
                            <div class="value">$901</div>
                            <div class="label">Due</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- page specific scripts -->
@push('module_scripts')
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.semanticui.min.js"></script>
    <script>
            $(function(){
                $('.datatable').DataTable();
            })

            $('.ui.dropdown')
                .dropdown()
            ;

            function togglestats(){
                var stats = document.getElementById('stats');
                if(stats.style.display == 'none'){
                    document.getElementById('stats').style.display = 'block';
                    document.getElementById('statsbutton').className = 'ui circular tiny right floated icon button';
                }
                else{
                    document.getElementById('stats').style.display = 'none';
                    document.getElementById('statsbutton').className = 'ui circular tiny grey basic right floated icon button';
                }
            }
    </script>
<!-- end page specific scripts -->
@endpush
