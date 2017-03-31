@extends('layouts.app') @section('content')
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
                    <img src="{{url('placeholderbill.jpg')}}" id="bill" onmousedown="getCoordinates(event)" onmouseup="getCoordsAgain(event)" onmouseout="coordsFailSafe(event)" onmousemove="getChangingCoords(event)">
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
            <form class="ui edit-record form" id="edit-record">
                <div class="ui tiny error message" id="errormsg"></div>
                
                <div class="ui fluid four item compact labeled icon menu">
                    <a class="select item" id="issue" onclick="test('#selidate');">
                        <i class="grey edit icon" id="issuedateicon"></i>
                        Issue<br>Date
                    </a>
                    <a class="select item" id="period" onclick="test('#selrperiod');">
                        <i class="grey edit icon" id="recordperiodicon"></i>
                        Record<br>Period
                    </a>
                    <a class="select item" id="duedate" onclick="test('#selddate');">
                        <i class="grey edit icon" id="duedateicon"></i>
                        Due<br>Date
                    </a>
                    <a class="select item" id="amtdue" onclick="test('#selamtdue');">
                        <i class="grey edit icon" id="amtdueicon"></i>
                        Amount<br>Due
                    </a>
                </div>                
                <br><br>

                <!--ADDED DEVICE-RESPONSIVE STUFF FOR FUN-->
                <div class="ui equal width grid">
                    <div class="computer only column">
                        <div class="actions">
                            <button class="ui black cancel right floated button" type="reset" onclick="window.location.href=document.referrer;">Cancel</button>
                            <button class="ui right floated button" type="reset" onclick="$('form').form('clear'); $('.form .message').html(''); resetAllRects();">Reset</button>
                            <button class="ui positive right floated button" type="submit">Submit</button>
                        </div>
                    </div>

                    <div class="mobile only column">
                        <button class="ui fluid positive button" type="submit">Submit</button>
                    </div>
                    <div class="mobile only column">
                        <button class="ui fluid button" type="reset" onclick="$('form').form('clear'); $('.form .message').html(''); resetAllRects();">Reset</button>
                    </div>
                    <div class="mobile only column">
                        <button class="ui fluid black cancel button" type="reset" onclick="window.location.href=document.referrer;">Cancel</button>
                    </div>

                    <div class="tablet only column">
                        <div class="actions">
                            <button class="fluid compact ui positive button" type="submit" style="margin-bottom: 10px;">Submit</button>
                            <button class="fluid compact ui button" type="reset" onclick="$('form').form('clear'); $('.form .message').html(''); resetAllRects();" style="margin-bottom: 10px;">Reset</button>
                            <button class="fluid compact ui black cancel button" type="reset" onclick="window.location.href=document.referrer;" style="margin-bottom: 10px;">Cancel</button>
                        </div>
                    </div>
                </div>
                <!--END OF RESPONSIVE BUTTONS LOL-->
            </form>
        </div>
    </div>
</div>

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
    var currPage = 1;
    var pageCount = 2; // should be length of the img array

    // placeholder images
    // to be replaced later with a single array of images/urls
    var img1 = "{{url('placeholderbill.jpg')}}";
    var img2 = "{{url('placeholderbill2.jpg')}}";
    
    billImg.onload = function() {
        ImgPos = FindPosition(billImg);
    }

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
    
    function test(id) {
        $('.selRect').removeClass("active");
        $(id).addClass("active");
    }

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
        currPage = Math.max(1, Math.min(currPage, pageCount));
        document.getElementById('pageno').innerHTML = currPage + " of 2";
        clearAllRects();
        renderRectsonPage(currPage);
        // if-else is hardcoded, replace with image array method later
        if (currPage == 1) {
            billImg.src = img1;
        } else {
            billImg.src = img2;
        }
    }

    // renders box given a set of coordinates
    function drawRect(box, coords) {
        if (coords == null) {
            return;
        }
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
        if (coords == null) {
            return;
        }
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
        if (coords == null) {
            return;
        }
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
        tempActField = $('.doing').get(0);

        if (tempActField.id == 'issue') {
            issueDateC = [PosX, PosY];
        } else if (tempActField.id == 'period') {
            recPeriodC = [PosX, PosY];
        } else if (tempActField.id == 'duedate') {
            dueDateC = [PosX, PosY];
        } else if (tempActField.id == 'amtdue') {
            amtDueC = [PosX, PosY];
        }
        selecting = true;
    }

    function getCoordsAgain(e) {
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

        if (tempActField.id == 'issue') {
            $('#issuedateicon').removeClass('edit');
            $('#issuedateicon').addClass('check circle outline');
            issueDateC = issueDateC.concat([PosX, PosY]);
            issueDateC = formatCoords(issueDateC);
            nIssueDateC = normalizeCoords(issueDateC);
            drawRect('selidate', issueDateC);
            document.getElementById('selidate').setAttribute('data-page', currPage);
        } else if (tempActField.id == 'period') {
            recPeriodC = recPeriodC.concat([PosX, PosY]);
            recPeriodC = formatCoords(recPeriodC);
            nRecPeriodC = normalizeCoords(recPeriodC);
            drawRect('selrperiod', recPeriodC);
            document.getElementById('selrperiod').setAttribute('data-page', currPage);
        } else if (tempActField.id == 'duedate') {
            dueDateC = dueDateC.concat([PosX, PosY]);
            dueDateC = formatCoords(dueDateC);
            nDueDateC = normalizeCoords(dueDateC);
            drawRect('selddate', dueDateC);
            document.getElementById('selddate').setAttribute('data-page', currPage);
        } else if (tempActField.id == 'amtdue') {
            amtDueC = amtDueC.concat([PosX, PosY]);
            amtDueC = formatCoords(amtDueC);
            nAmtDueC = normalizeCoords(amtDueC);
            drawRect('selamtdue', amtDueC);
            document.getElementById('selamtdue').setAttribute('data-page', currPage);
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

        if (tempActField.id == 'issue') {
            $('#issuedateicon').removeClass('grey');
            $('#issuedateicon').addClass('green');
            tempCoords = issueDateC.concat([PosX, PosY]);
            tempCoords = formatCoords(tempCoords);
            drawRect('selidate', tempCoords);
        } else if (tempActField.id == 'period') {
            tempCoords = recPeriodC.concat([PosX, PosY]);
            tempCoords = formatCoords(tempCoords);
            drawRect('selrperiod', tempCoords);
        } else if (tempActField.id == 'duedate') {
            tempCoords = dueDateC.concat([PosX, PosY]);
            tempCoords = formatCoords(tempCoords);
            drawRect('selddate', tempCoords);
        } else if (tempActField.id == 'amtdue') {
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

            if (tempActField.id == 'issue') {
                $('#issuedateicon').removeClass('edit');
                $('#issuedateicon').addClass('check circle outline');
                issueDateC = issueDateC.concat([PosX, PosY]);
                document.getElementById("temp").innerHTML = "Image size: " + billsize +
                    "<br>" + "Issue Date: " + issueDateC;
            } else if (tempActField.id == 'period') {
                recPeriodC = recPeriodC.concat([PosX, PosY]);
                document.getElementById("temp").innerHTML = "Record Period: " + recPeriodC;
            } else if (tempActField.id == 'duedate') {
                dueDateC = dueDateC.concat([PosX, PosY]);
                document.getElementById("temp").innerHTML = "Due Date: " + dueDateC;
            } else if (tempActField.id == 'amtdue') {
                amtDueC = amtDueC.concat([PosX, PosY]);
                document.getElementById("temp").innerHTML = "Amount Due: " + amtDueC;
            }

            //document.getElementById('temp').innerHTML = "Image size: " + billsize +
            //                                            "<br>" + "mouseupped outside image at: " + PosX + ", " + PosY;
        }
        selecting = false;
    }
</script>
@endsection
