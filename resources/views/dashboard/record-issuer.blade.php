@extends('layouts.app')
<!-- page specific styles -->
@push('module_styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.semanticui.min.css">
@endpush
<!-- page specific styles -->

@section('content')
    <!--CONTENT-->
    <div class="ui container" style="background:white; padding:90px 65px 65px 65px; min-height: 100vh;">
        <!-- ui grid -->
        <div class="ui grid">

            <div class="sixteen wide column">
                @component('partials.breadscrumb')
                    @slot('active_section')
                        {{ $record_issuer->name }}
                    @endslot
                @endcomponent
            </div>

            @if(empty(($records)))
                {{-- if there is no records --}}
                <div class="sixteen wide column">
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
                </div>

            @endif
        </div>
        <!-- end ui grid -->

        @if(!empty($records))
           {{-- if there are records --}}
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
                    @include('partials.recordEntry')
                @endforeach

                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="5" class="bordered center aligned">
                            <button class="ui circular blue add-record icon button" value="showModal">
                                <i class="icon plus"></i>
                            </button>
                            <span>Add new record</span>
                        </td>
                    </tr>
                </tfoot>

            </table>
        @endif
        <!-- modal start -->
        <div class="ui small add-record modal">
            <i class="close icon"></i>
            <div class="header">Add new record</div>

            <!-- modal content -->
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
                </div><!-- end ui fluid input -->
            </div><!-- end modal content -->

            <div class="actions">
                <div class="ui green approve button" data-value="yes">Add</div>
                <div class="ui black cancel button" data-value="no">Cancel</div>
            </div>
        </div><!-- modal end -->
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
    </script>
<!-- end page specific scripts -->
@endpush