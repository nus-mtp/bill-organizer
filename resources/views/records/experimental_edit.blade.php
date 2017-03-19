@extends('layouts.app')

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
                        @foreach($temp_record->pages as $page)
                            <p style="display: none;">{{route('show_temp_record_page', $page->id)}}</p>
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
                <div class="ui message" id="temp">for test</div>
                <form id="test" class="ui edit-record form" id="edit-record">
                    <div class="ui tiny error message" id="errormsg"></div>
                    <div class="field">
                        <label>Issue Date <atn>*</atn></label>
                        <input type="text" name="issuedate" placeholder="Issue Date" id="test_issue_date" onfocus="clearError();">
                    </div>
                    <div class="field">
                        <label>Record Period</label>
                        <input type="text" name="recordperiod" placeholder="Record Period" id="test_period" onfocus="clearError();">
                    </div>
                    @if($is_bill)
                        <div class="field">
                            <label>Due Date</label>
                            <input type="text" name="duedate" placeholder="Due Date" id="test_due_date" onfocus="clearError();">
                        </div>
                    @endif
                    <div class="field">
                        <label>Amount Due <atn>*</atn></label>
                        <input type="text" name="amtdue" placeholder="e.g 400" id="test_amount" onfocus="clearError();">
                    </div>
                    <tnc>
                        <atn>*</atn> <i>Indicates required field</i><br><br></tnc>
                    {{--<div class="actions">--}}
                    {{--<button class="ui positive button" type="submit">Submit</button>--}}
                    {{--<button class="ui button" type="reset" onclick="$('form').form('clear'); $('.form .message').html(''); resetAllRects();">Reset</button>--}}
                    {{--<button class="ui black cancel button" type="reset" onclick="window.location.href=document.referrer;">Cancel</button>--}}
                    {{--</div>--}}
                </form>

                <div>
                    <form class="ui form" id="coords-form" action="{{ route('extract_coords', $temp_record) }}" method="POST">
                        {{ csrf_field() }}
                        @foreach($field_area_inputs as $key => $val)
                            <input type="hidden" name="{{$key}}" id="{{$key}}" value="{{$val}}">
                        @endforeach
                        @if(!$edit_value_mode)
                            <div class="actions">
                                <button class="ui positive button" type="submit">Submit</button>
                                <button class="ui button" type="reset" onclick="$('form#test').form('clear'); $('.form .message').html(''); resetAllRects();">Reset</button>
                                <button class="ui black cancel button" type="reset" onclick="window.location.href=document.referrer;">Cancel</button>
                            </div>
                        @endif
                    </form>
                </div>

                @if($edit_value_mode)
                    <br><br>
                    <div>
                        Real values
                        <form class="ui form" action="{{ route('confirm_values', $temp_record) }}" method="POST">
                            {{ csrf_field() }}
                            <div class="field">
                                <label>Issue Date</label>
                                <input type="date" name="issue_date" placeholder="Issue Date" id="issue_date"
                                       value="{{$temp_record->issue_date->toDateString()}}">
                            </div>
                            <div class="field">
                                <label>Record Period</label>
                                <input type="month" name="period" placeholder="Period" id="period"
                                       value="{{$temp_record->asdperiod->format('Y-m')}}">
                            </div>
                            @if($is_bill)
                                <div class="field">
                                    <label>Due Date</label>
                                    <input type="date" name="due_date" placeholder="Due Date" id="due_date"
                                           value="{{$temp_record->due_date->toDateString()}}">
                                </div>
                            @endif
                            <div class="field">
                                <label>Amount Due</label>
                                <input type="text" name="amount" placeholder="e.g 400" id="amount"
                                       value="{{$temp_record->amount}}">
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
<script type="text/javascript">
    // box coordinates used for rendering
    var issueDateC;
    var recPeriodC;
    var dueDateC;
    var amtDueC;
    // normalized versions of the box coordinates
    var nIssueDateC;
    var nRecPeriodC;
    var nDueDateC;
    var nAmtDueC;

    var billImg = document.getElementById('bill');
    var tempActField;
    var selecting = false;
    var currPage = 0; // page is 0-indexed
    var pageCount = 2; // should be length of the img array

    // placeholder images
    // to be replaced later with a single array of images/urls
    // TEDDY
    {{--var img1 = "{{url('placeholderbill.jpg')}}";--}}
    {{--var img2 = "{{url('placeholderbill2.jpg')}}";--}}
    var img_urls = [];
    // END OF TEDDY

    // TEDDY
    $(document).ready(function() {
        var bill_p_selector = ".bill-image #bill-wrapper > p"
        $(bill_p_selector).each(function(_) {
            var this_url = this.innerText;
            img_urls.push(this_url);
        });
        pageCount = $(bill_p_selector).length;

        changePage(0);

        // registerListeners();
    });

    /*
    function registerListeners() {
        $("form#coords-form button[type='submit']").click(function() {
            event.preventDefault();
            var is_bill = Boolean("{{$is_bill}}");
            var attrs = ['issue_date', 'period', 'amount'];
            if (is_bill) {
                attrs.push('due_date');
            }

            for (var i = 0; i < attrs.length; i++) {
                var dest_input_attrs = ['page', 'x', 'y', 'w', 'h'];
                var src_input_name = "test_" + attrs[i];
                // TODO: Yan Ling fix the hard-coded page
                var page = 0, x, y, w, h; // page are hard-coded as 0 for now
                var coords = $("input#" + src_input_name).val().split(",");
                x = coords[0];
                y = coords[1];
                w = coords[2] - coords[0];
                h = coords[3] - coords[1];

            }
        })
    }*/
    // END OF TEDDY

    // disable default image drag action so you can drag select box later
    billImg.ondragstart = function(event) {
        event.preventDefault();
    };
    window.onresize = function(event) {
        issueDateC = resizeNCoords(nIssueDateC);
        recPeriodC = resizeNCoords(nRecPeriodC);
        dueDateC = resizeNCoords(nDueDateC);
        amtDueC = resizeNCoords(nAmtDueC);

        changePage(0);
    };

    function displayError(message) {
        // uses the error message that comes with the semantic ui form
        document.getElementById("errormsg").innerHTML = message;
        document.getElementById("errormsg").style.display = "block";
    }

    function clearError() {
        document.getElementById("errormsg").style.display = "none";
    }

    // change bill page view and any related boxes
    function changePage(num) {
        currPage += num;
        // to make sure 1 <= currPage <= pageCount
        // TEDDY
        currPage = Math.max(0, Math.min(currPage, pageCount-1));
        document.getElementById('pageno').innerHTML = currPage+1 + " of " + pageCount;
        // END OF TEDDY
        clearAllRects();
        renderRectsonPage(currPage);
        // if-else is hardcoded, replace with image array method later
        // TEDDY
        billImg.src = img_urls[currPage];
        /*
        if (currPage == 1) {
            billImg.src = img1;
        }
        else{
            billImg.src = img2;
        }
        */
        // END OF TEDDY
    }

    // renders box given a set of coordinates
    function drawRect(box, coords) {
        if (coords == null) { return; }
        document.getElementById(box).style.display = 'block';
        document.getElementById(box).style.left = coords[0] + 'px';
        document.getElementById(box).style.top = coords[1] + 'px';
        document.getElementById(box).style.width = (coords[2] - coords[0]) + 'px';
        document.getElementById(box).style.height = (coords[3] - coords[1]) + 'px';
    }

    // renders boxes according to what page user is on
    function renderRectsonPage(pagenum) {
        if (document.getElementById('selidate').getAttribute('data-page') == pagenum) {
            drawRect('selidate', issueDateC);
        }
        if (document.getElementById('selrperiod').getAttribute('data-page') == pagenum) {
            drawRect('selrperiod', recPeriodC);
        }
        if (document.getElementById('selddate').getAttribute('data-page') == pagenum) {
            drawRect('selddate', dueDateC);
        }
        if (document.getElementById('selamtdue').getAttribute('data-page') == pagenum) {
            drawRect('selamtdue', amtDueC);
        }
    }

    function clearRect(box) {
        document.getElementById(box).style.display = 'none';
    }

    // clears any rendered boxes
    function clearAllRects() {
        clearRect('selidate');
        clearRect('selrperiod');
        clearRect('selddate');
        clearRect('selamtdue');
    }

    // clear any rendered boxes and their related coordinates
    function resetAllRects() {
        clearAllRects();
        issueDateC = null;
        recPeriodC = null;
        dueDateC = null;
        amtDueC = null;
        nIssueDateC = null;
        nRecPeriodC = null;
        nDueDateC = null;
        nAmtDueC = null;
    }

    //normal selection is top-bottom or left-right
    //this function standardizes bottom-top or right-left selection
    function formatCoords(coords) {
        if (coords[0] > coords[2] && coords[1] > coords[3]) { //btmright to topleft
            temp = [coords[2], coords[3], coords[0], coords[1]];
            return temp;
        } else if (coords[0] > coords[2]) { //topright to btmleft
            temp = [coords[2], coords[1], coords[0], coords[3]];
            return temp;
        } else if (coords[1] > coords[3]) { //btmleft to topright
            temp = [coords[0], coords[3], coords[2], coords[1]];
            return temp;
        } else {
            return coords;
        }
    }

    // return coords ratios
    function normalizeCoords(coords) {
        if (coords == null) { return; }
        var temp = [0, 0, 0, 0];
        var width = billImg.width;
        var height = billImg.height;
        temp[0] = coords[0] / width;
        temp[1] = coords[1] / height;
        temp[2] = coords[2] / width;
        temp[3] = coords[3] / height;
        return temp;
    }

    // converts normalized coords to rendering coords
    function resizeNCoords(coords) {
        if (coords == null) { return; }
        var temp = [0, 0, 0, 0];
        var width = billImg.width;
        var height = billImg.height;
        temp[0] = coords[0] * width;
        temp[1] = coords[1] * height;
        temp[2] = coords[2] * width;
        temp[3] = coords[3] * height;
        return temp;
    }

    function FindPosition(oElement) {
        if (typeof(oElement.offsetParent) != "undefined") {
            for (var posX = 0, posY = 0; oElement; oElement = oElement.offsetParent) {
                posX += oElement.offsetLeft;
                posY += oElement.offsetTop;
            }
            return [posX, posY];
        } else {
            return [oElement.x, oElement.y];
        }
    }

    var ImgPos = FindPosition(billImg);

    function getCoordinates(e) {
        var PosX = 0;
        var PosY = 0;
        if (!e) var e = window.event;
        if (e.pageX || e.pageY) {
            PosX = e.pageX;
            PosY = e.pageY;
        } else if (e.clientX || e.clientY) {
            PosX = e.clientX + document.body.scrollLeft +
                document.documentElement.scrollLeft;
            PosY = e.clientY + document.body.scrollTop +
                document.documentElement.scrollTop;
        }
        PosX = PosX - ImgPos[0];
        PosY = PosY - ImgPos[1];
        var activeField = document.activeElement;
        tempActField = activeField;
        //to prevent validation error from acting up
        tempActField.value = "selecting...";

        if (document.activeElement.id == 'test_issue_date') {
            issueDateC = [PosX, PosY];
        } else if (document.activeElement.id == 'test_period') {
            recPeriodC = [PosX, PosY];
        } else if (document.activeElement.id == 'test_due_date') {
            dueDateC = [PosX, PosY];
        } else if (document.activeElement.id == 'test_amount') {
            amtDueC = [PosX, PosY];
        }
        selecting = true;
    }

    function getCoordsAgain(e) {
        if (!selecting) { return; }

        var PosX = 0;
        var PosY = 0;
        if (!e) var e = window.event;
        if (e.pageX || e.pageY) {
            PosX = e.pageX;
            PosY = e.pageY;
        } else if (e.clientX || e.clientY) {
            PosX = e.clientX + document.body.scrollLeft +
                document.documentElement.scrollLeft;
            PosY = e.clientY + document.body.scrollTop +
                document.documentElement.scrollTop;
        }
        PosX = PosX - ImgPos[0];
        PosY = PosY - ImgPos[1];

        if (tempActField.id == 'test_issue_date_date') {
            issueDateC = issueDateC.concat([PosX, PosY]);
            issueDateC = formatCoords(issueDateC);
            nIssueDateC = normalizeCoords(issueDateC);
            drawRect('selidate', issueDateC);
            document.getElementById('selidate').setAttribute('data-page', currPage);
            tempActField.value = issueDateC;
        } else if (tempActField.id == 'test_period') {
            recPeriodC = recPeriodC.concat([PosX, PosY]);
            recPeriodC = formatCoords(recPeriodC);
            nRecPeriodC = normalizeCoords(recPeriodC);
            drawRect('selrperiod', recPeriodC);
            document.getElementById('selrperiod').setAttribute('data-page', currPage);
            tempActField.value = recPeriodC;
        } else if (tempActField.id == 'test_due_date') {
            dueDateC = dueDateC.concat([PosX, PosY]);
            dueDateC = formatCoords(dueDateC);
            nDueDateC = normalizeCoords(dueDateC);
            drawRect('selddate', dueDateC);
            document.getElementById('selddate').setAttribute('data-page', currPage);
            tempActField.value = dueDateC;
        } else if (tempActField.id == 'test_amount') {
            amtDueC = amtDueC.concat([PosX, PosY]);
            amtDueC = formatCoords(amtDueC);
            nAmtDueC = normalizeCoords(amtDueC);
            drawRect('selamtdue', amtDueC);
            document.getElementById('selamtdue').setAttribute('data-page', currPage);
            tempActField.value = amtDueC;
        } else {
            displayError("Please click on a field before selecting");
        }
        selecting = false;
    }

    function getChangingCoords(e) {
        if (!selecting) {
            return;
        }
        var PosX = 0;
        var PosY = 0;
        if (!e) var e = window.event;
        if (e.pageX || e.pageY) {
            PosX = e.pageX;
            PosY = e.pageY;
        } else if (e.clientX || e.clientY) {
            PosX = e.clientX + document.body.scrollLeft +
                document.documentElement.scrollLeft;
            PosY = e.clientY + document.body.scrollTop +
                document.documentElement.scrollTop;
        }
        PosX = PosX - ImgPos[0];
        PosY = PosY - ImgPos[1];
        var tempCoords;

        if (tempActField.id == 'test_issue_date') {
            tempCoords = issueDateC.concat([PosX, PosY]);
            tempCoords = formatCoords(tempCoords);
            drawRect('selidate', tempCoords);
        } else if (tempActField.id == 'test_period') {
            tempCoords = recPeriodC.concat([PosX, PosY]);
            tempCoords = formatCoords(tempCoords);
            drawRect('selrperiod', tempCoords);
        } else if (tempActField.id == 'test_due_date') {
            tempCoords = dueDateC.concat([PosX, PosY]);
            tempCoords = formatCoords(tempCoords);
            drawRect('selddate', tempCoords);
        } else if (tempActField.id == 'test_amount') {
            tempCoords = amtDueC.concat([PosX, PosY]);
            tempCoords = formatCoords(tempCoords);
            drawRect('selamtdue', tempCoords);
        } else {
            displayError("Please click on a field before selecting");
        }
    }

    function coordsFailSafe(e) {
        if (selecting) {
            var PosX = 0;
            var PosY = 0;
            if (!e) var e = window.event;
            if (e.pageX || e.pageY) {
                PosX = e.pageX;
                PosY = e.pageY;
            } else if (e.clientX || e.clientY) {
                PosX = e.clientX + document.body.scrollLeft +
                    document.documentElement.scrollLeft;
                PosY = e.clientY + document.body.scrollTop +
                    document.documentElement.scrollTop;
            }
            PosX = PosX - ImgPos[0];
            PosY = PosY - ImgPos[1];

            var billsize = [document.getElementById('bill').width, document.getElementById('bill').height];

            //adjust coordinates to fit in image
            if (PosX > billsize[0]) {
                PosX = billsize[0];
            }
            if (PosY > billsize[1]) {
                PosY = billsize[1];
            } else if (PosX < 0) {
                PosX = 0;
            } else if (PosY < 0) {
                PosY = 0;
            }

            if (tempActField.id == 'test_issue_date') {
                issueDateC = issueDateC.concat([PosX, PosY]);
                document.getElementById("temp").innerHTML = "Image size: " + billsize +
                    "<br>" + "Issue Date: " + issueDateC;
            } else if (tempActField.id == 'test_period') {
                recPeriodC = recPeriodC.concat([PosX, PosY]);
                document.getElementById("temp").innerHTML = "Record Period: " + recPeriodC;
            } else if (tempActField.id == 'test_due_date') {
                dueDateC = dueDateC.concat([PosX, PosY]);
                document.getElementById("temp").innerHTML = "Due Date: " + dueDateC;
            } else if (tempActField.id == 'test_amount') {
                amtDueC = amtDueC.concat([PosX, PosY]);
                document.getElementById("temp").innerHTML = "Amount Due: " + amtDueC;
            }

            //document.getElementById('temp').innerHTML = "Image size: " + billsize +
            //                                            "<br>" + "mouseupped outside image at: " + PosX + ", " + PosY;
        }
        selecting = false;
    }

</script>
@endpush
