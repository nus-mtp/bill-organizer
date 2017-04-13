@extends('layouts.app')

@push('module_styles')
<style>
    #errormsg {
        display: none;
    }
</style>
@endpush

@section('content')
    <!--CONTENT-->
    <div class="ui container">
        <div class="ui stackable grid">
            <div class="sixteen wide column">
                <div class="ui breadcrumb">
                    <!-- TODO: Extract breadcrumbs and add links-->
                    <span class="section">Home</span>
                    <i class="right angle icon divider"></i>
                    <span class="section">Dashboard</span>
                    <i class="right angle icon divider"></i>
                    <span class="section">[insert billing organisation]</span>
                    <i class="right angle icon divider"></i>
                    <span class="active section">Edit Record</span>
                </div>
            </div>

            <div class="ten wide column">
                <div class="bill-image">
                    <div class="selRect" id="selidate" data-page="0"></div>
                    <div class="selRect" id="selrperiod" data-page="0"></div>
                    <div class="selRect" id="selddate" data-page="0"></div>
                    <div class="selRect" id="selamtdue" data-page="0"></div>
                    <!--Might remove wrapper later because might not need-->
                    <div id="bill-wrapper">
                        {{--<img src="{{url('placeholderbill.jpg')}}" id="bill" onmousedown="getCoordinates(event)" onmouseup="getCoordsAgain(event)" onmouseout="coordsFailSafe(event)" onmousemove="getChangingCoords(event)">--}}
                        <img id="bill" onmousedown="getCoordinates(event)" onmouseup="getCoordsAgain(event)" onmouseout="coordsFailSafe(event)" onmousemove="getChangingCoords(event)">
                        <p style="display: none;" id="is-bill">{{$is_bill}}</p>
                        @foreach($record->pages as $page)
                            <p style="display: none;" class="img-url">{{route('show_record_page', $page->id)}}</p>
                        @endforeach
                    </div>
                </div>
                <center>
                    <div class="ui pagination menu">
                        <a class="item" onclick="changePage(-1)"><i class="caret left icon"></i></a>
                        <a class="disabled item" id="pageno">1 of 2</a>
                        <a class="item" onclick="changePage(1)"><i class="caret right icon"></i></a>
                    </div>
                </center>
            </div>

            <div class="six wide column">
                <div class="ui tiny error message" id="errormsg"></div>
                    @if(!$edit_value_mode)
                    <div class="ui pointing below label">
                        @if($is_bill)
                            Click on an item below, then select the corresponding field in the bill
                        @else
                            Click on an item below, then select the corresponding field in the bank statement
                        @endif
                    </div>
                        @if($is_bill)
                        <div class="ui fluid four item compact labeled icon menu">
                            <a class="select item" id="issue" onclick="selAnother('#selidate');">
                                <i class="grey edit icon" id="issuedateicon"></i>                        Issue<br>Date
                            </a>
                            <a class="select item" id="period" onclick="selAnother('#selrperiod');">
                                <i class="grey edit icon" id="rperiodicon"></i>                        Bill<br>Period
                            </a>
                            <a class="select item" id="duedate" onclick="selAnother('#selddate');">
                                <i class="grey edit icon" id="duedateicon"></i>
                                Due<br>Date
                            </a>
                            <a class="select item" id="amtdue" onclick="selAnother('#selamtdue');">
                                <i class="grey edit icon" id="amtdueicon"></i>
                                Amount<br>Due
                            </a>
                        </div>
                        @endif
                <!--for banks-->
                        @if(!$is_bill)
                        <div class="ui fluid three item compact labeled icon menu">
                            <a class="select item" id="issue" onclick="selAnother('#selidate');">
                                <i class="grey edit icon" id="issuedateicon"></i>                        Issue Date
                            </a>
                            <a class="select item" id="period" onclick="selAnother('#selrperiod');">
                                <i class="grey edit icon" id="rperiodicon"></i>                        Bank Statement Period
                            </a>
                            <a class="select item" id="amtdue" onclick="selAnother('#selamtdue');">
                                <i class="grey edit icon" id="amtdueicon"></i>
                                Balance
                            </a>
                        </div>
                        @endif
                        <br><br>
                        @endif
                
                <!--hidden inputs below-->
                <div>
                    <form class="ui form" id="coords-form" action="{{ route('store_template', $record) }}" method="POST">
                        {{ csrf_field() }}
                        @foreach($field_area_inputs as $key => $val)
                            <div class="field">
                                <input type="hidden" name="{{$key}}" id="{{$key}}" value="{{$val}}">
                            </div>
                        @endforeach
                        @if(!$edit_value_mode)
                            <div class="actions">
                                <button class="ui positive ocr button" type="submit">Submit</button>
                                <button class="ui button" type="reset" onclick="$('form#coords-form').form('clear'); $('.form .message').html(''); resetAllRects();$('.icon', '.select').attr('class', 'grey edit icon');">Reset</button>
                                <button class="ui black cancel button" type="reset" onclick="window.location.href=document.referrer;">Cancel</button>
                            </div>
                        @endif
                    </form>
                </div>

                @if($edit_value_mode)
                    <div>
                        <form class="ui form" action="{{ route('confirm_values', $record) }}" method="POST">
                            {{ csrf_field() }}
                            <div class="field">
                                <label for="issue_date">Issue Date</label>
                                <div class="ui calendar">
                                    <div class="ui input left icon">
                                        <i class="calendar icon"></i>
                                        <input name="issue_date" type="text" id="issue_date" placeholder="yyyy-mm-dd"
                                               value="{{$record->issue_date ? $record->issue_date->toDateString() : null}}">
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <label for="period">Record Period</label>
                                <div class="ui calendar-month">
                                    <div class="ui input left icon">
                                        <i class="calendar icon"></i>
                                        <input name="period" type="text" id="period" placeholder="yyyy-mm"
                                               value="{{$record->period ? $record->period->format('Y-m') : null}}">
                                    </div>
                                </div>
                            </div>
                            @if($is_bill)
                                <div class="field">
                                    <label for="issue_date">Due Date</label>
                                    <div class="ui calendar">
                                        <div class="ui input left icon">
                                            <i class="calendar icon"></i>
                                            <input name="due_date" type="text" id="due_date" placeholder="yyyy-mm-dd"
                                                   value="{{$record->due_date ? $record->due_date->toDateString() : null}}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="field">
                                @if($is_bill)
                                    <label>Amount Due</label>
                                @endif
                                @if(!$is_bill)
                                    <label>Balance</label>
                                @endif
                                <input type="text" name="amount" placeholder="e.g 400" id="amount"
                                       value="{{$record->amount}}">
                            </div>
                            <div class="actions">
                                <button class="ui positive button" type="submit">Submit</button>
                                <button class="ui black button" type="cancel">Cancel</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('module_scripts')
<script type="text/javascript" src="/js/modules/edit_record.js"></script>
@endpush
