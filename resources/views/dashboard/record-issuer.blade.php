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
                            @include('records.record')
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
                $("#delete-record")
            })


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
