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

                    <button class="ui circular mini blue right floated button" id="statsbutton" onclick="togglestats();"><i class="bar chart icon"></i>
                    </button>

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
                        @include('records.record')
                    @endforeach

                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="5" class="bordered centere aligned">
                                <button class="ui circular blue add-record icon button" value="showModal">
                                    <i class="icon plus"></i>
                                </button>
                                <span>Add new record</span>
                            </td>
                        </tr>
                    </tfoot>

                </table>
            @endif

            @include('records.addRecordModal')
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
                            <div class="item" data-value="0">This month</div>
                            <div class="item" data-value="1">This year</div>
                            <div class="item" data-value="2">Past 2 years</div>
                            <div class="item" data-value="3">All of time</div>
                            <div class="item" data-value="4">Pre Big Bang</div>
                        </div>
                    </div>

                    <div class="ui statistics">
                        <div class="red statistic">
                            <div class="value">0</div>
                            <div class="label">Total bills filed</div>
                            <!-- total number of filed records -->
                        </div>

                        <div class="red statistic">
                            <div class="value">$901</div>
                            <div class="label">Owed</div>
                        </div>

                        <div class="red statistic">
                            <div class="value"><i class="frown icon"></i></div>
                            <div class="label">Sadded</div>
                        </div>
                    </div><!-- end of ui statistics -->

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
                    document.getElementById('statsbutton').className = 'ui circular mini blue right floated button';
                }
                else{
                    document.getElementById('stats').style.display = 'none';
                    document.getElementById('statsbutton').className = 'ui circular mini right floated button';
                }
            }
    </script>
<!-- end page specific scripts -->
@endpush
