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

                    <button class="ui circular tiny right floated icon button" id="statsbutton"><i class="bar chart icon"></i>
                    </button>

                    <div class="ui basic segment">

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
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                            @foreach($records as $record)
                                @include('records.record')
                            @endforeach

                            </tbody>

                        </table>

                        @include('records.addRecordModal')
                    </div><!-- basic segment end -->
                </div><!-- column end -->
                @include('stats.sidebar')
            </div> <!-- end of ui equal width grid -->
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
        onDashboardLoad(window);
    })
    </script>
<!-- end page specific scripts -->
@endpush
