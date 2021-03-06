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

                    @component('partials.breadcrumbs')
                        @slot('active_section')
                          <a href="{{route('show_record_issuer',['record_issuer'=>$record_issuer])}}">{{$record_issuer->name}}</a>
                        @endslot
                    @endcomponent

                    <button class="ui circular tiny right floated icon button" id="statsbutton"><i class="bar chart icon"></i>
                    </button>

                    <div class="ui basic segment">
                        <div class="ui right floated blue labeled add-record button" style="margin-top:13px;">
                            <div class="ui blue icon button">
                                <i class="add icon"></i>
                            </div>
                            @if($type === 'billing organization')
                                <a class="ui basic blue label">Add new bill</a>
                            @else
                                <a class="ui basic blue label">Add new statement</a>
                            @endif
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

    {{-- TODO: Once it's confirmed that we don't need this old method of storing record, delete--}}
    {{-- TODO: Kenan move this to app.js --}}
    {{--<script>--}}
        {{--$( document ).ready(function() {--}}
            {{--$('.experimental-add-record.button').click(function() {--}}
                {{--$('.ui.modal.experimental-add-record').modal({--}}
                    {{--onApprove: function() {--}}
                        {{--$('form#experimental-add-record').submit();--}}
                    {{--}--}}
                {{--}).modal('show');--}}
            {{--});--}}
        {{--});--}}
    {{--</script>--}}
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.semanticui.min.js"></script>
    <script>
    $(function(){
        onRecordsPageLoad(window);
        $('.datatable').DataTable();
    })
    </script>
<!-- end page specific scripts -->
@endpush
