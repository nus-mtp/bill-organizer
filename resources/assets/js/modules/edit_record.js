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
var ImgPos;
var img_urls = [];
// END OF TEDDY

// TEDDY
// TODO: If coords exists, should populate the input fields that show the coords too
$(document).ready(function () {
    var billPSelector = ".bill-image #bill-wrapper > p.img-url";
    $(billPSelector).each(function (_) {
        var this_url = this.innerText;
        img_urls.push(this_url);
    });
    pageCount = $(billPSelector).length;

    loadAttrstoBox('issue_date', 'selidate');
    loadAttrstoBox('period', 'selrperiod');
    loadAttrstoBox('amount', 'selamtdue');

    var isBillSelector = ".bill-image #bill-wrapper > p#is-bill";
    var isBill = Boolean($(isBillSelector).text());
    if (isBill) {
        loadAttrstoBox('due_date', 'selddate');
    }

    billImg.onload = function () {
        issueDateC = resizeNCoords(nIssueDateC);
        recPeriodC = resizeNCoords(nRecPeriodC);
        dueDateC = resizeNCoords(nDueDateC);
        amtDueC = resizeNCoords(nAmtDueC);

        ImgPos = FindPosition(billImg);

        clearAllRects();
        renderRectsonPage(currPage);
    }
    billImg.src = img_urls[currPage];
    
    onEditPageLoad(window);
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
billImg.ondragstart = function (event) {
    event.preventDefault();
};
window.onresize = function (event) {
    issueDateC = resizeNCoords(nIssueDateC);
    recPeriodC = resizeNCoords(nRecPeriodC);
    dueDateC = resizeNCoords(nDueDateC);
    amtDueC = resizeNCoords(nAmtDueC);

    changePage(0);
};

function selAnother(id) {
    $('.selRect').removeClass("active");
    $(id).addClass("active");

    var message;
    if (id === '#selidate') {
        message = "Draw a box around the date when the bill was issued. Eg: 1 Jan 2001"
    } else if (id === '#selrperiod') {
        message = "Draw a box around the period that this bill/bank statement covers. Eg: January 2001"
    } else if (id === '#selddate') {
        message = "Draw a box around the due date of the bill. Eg: 10 Jan 2001"
    } else if (id === '#selamtdue') {
        message = "Draw a box around the amount due/balance. Eg: $100"
    }

    if (message !== undefined) {
        $('#instruction-section').css('display', 'block');
        $('#template-instruction').text(message);
    }
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
    // TEDDY
    currPage = Math.max(0, Math.min(currPage, pageCount - 1));
    document.getElementById('pageno').innerHTML = currPage + 1 + " of " + pageCount;
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

// TODO: Should add validation for coords
// use this to load attribute when page loads for the first time
function loadAttrstoBox(input, box) {
    var id = input + "_page";
    document.getElementById(box).setAttribute('data-page', document.getElementById(id).value);
    var hasTemplate = (document.getElementById(box).getAttribute('data-page') != 0);
    temp = [0, 0, 0, 0, 0, 0];
    id = input + "_x";
    temp[0] = Number(document.getElementById(id).value);
    id = input + "_y"
    temp[1] = Number(document.getElementById(id).value);
    id = input + "_w"
    temp[4] = Number(document.getElementById(id).value);
    id = input + "_h"
    temp[5] = Number(document.getElementById(id).value);
    temp[2] = temp[0] + temp[4];
    temp[3] = temp[1] + temp[5];
    if (box == 'selidate') {
        nIssueDateC = temp;
        if (hasTemplate) {
            $('#issuedateicon').removeClass('grey edit');
            $('#issuedateicon').addClass('green check circle outline');
        }
    } else if (box == 'selrperiod') {
        nRecPeriodC = temp;
        if (hasTemplate) {
            $('#rperiodicon').removeClass('grey edit');
            $('#rperiodicon').addClass('green check circle outline');
        }
    } else if (box == 'selddate') {
        nDueDateC = temp;
        if (hasTemplate) {
            $('#duedateicon').removeClass('grey edit');
            $('#duedateicon').addClass('green check circle outline');
        }
    } else if (box == 'selamtdue') {
        nAmtDueC = temp;
        if (hasTemplate) {
            $('#amtdueicon').removeClass('grey edit');
            $('#amtdueicon').addClass('green check circle outline');
        }
    } else {
        return;
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
    var temp = [0, 0, 0, 0, 0, 0];
    var width = billImg.width;
    var height = billImg.height;
    temp[0] = coords[0] / width;
    temp[1] = coords[1] / height;
    temp[2] = coords[2] / width;
    temp[3] = coords[3] / height;
    temp[4] = temp[2] - temp[0];
    temp[5] = temp[3] - temp[1];
    return temp;
}

// converts normalized coords to rendering coords
function resizeNCoords(coords) {
    if (coords == null) {
        return;
    }
    var temp = [0, 0, 0, 0, 0, 0];
    var width = billImg.width;
    var height = billImg.height;
    temp[0] = coords[0] * width;
    temp[1] = coords[1] * height;
    temp[2] = coords[2] * width;
    temp[3] = coords[3] * height;
    temp[4] = temp[2] - temp[0];
    temp[5] = temp[3] - temp[1];
    return temp;
}

// given input (e.g. 'issue_date') and its related values,
// fill in the hidden attribute fields for this input
function updateHiddenInput(input, page, x, y, w, h) {
    var id = input + "_page";
    document.getElementById(id).value = page;
    id = input + "_x";
    document.getElementById(id).value = x;
    id = input + "_y"
    document.getElementById(id).value = y;
    id = input + "_w"
    document.getElementById(id).value = w;
    id = input + "_h"
    document.getElementById(id).value = h;
}

function FindPosition(oElement) {
    if (typeof (oElement.offsetParent) != "undefined") {
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

    if (tempActField) {
        if (tempActField.id == 'issue') {
            issueDateC = [PosX, PosY];
        } else if (tempActField.id == 'period') {
            recPeriodC = [PosX, PosY];
        } else if (tempActField.id == 'duedate') {
            dueDateC = [PosX, PosY];
        } else if (tempActField.id == 'amtdue') {
            amtDueC = [PosX, PosY];
        }
    } else {
        displayError("Please click on an item below before selecting");
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
        // fill hidden fields
        updateHiddenInput('issue_date', currPage, nIssueDateC[0], nIssueDateC[1], nIssueDateC[4], nIssueDateC[5]);

    } else if (tempActField.id == 'period') {
        $('#rperiodicon').removeClass('edit');
        $('#rperiodicon').addClass('check circle outline');
        recPeriodC = recPeriodC.concat([PosX, PosY]);
        recPeriodC = formatCoords(recPeriodC);
        nRecPeriodC = normalizeCoords(recPeriodC);
        drawRect('selrperiod', recPeriodC);
        document.getElementById('selrperiod').setAttribute('data-page', currPage);
        // fill hidden fields
        updateHiddenInput('period', currPage, nRecPeriodC[0], nRecPeriodC[1], nRecPeriodC[4], nRecPeriodC[5]);

    } else if (tempActField.id == 'duedate') {
        $('#duedateicon').removeClass('edit');
        $('#duedateicon').addClass('check circle outline');
        dueDateC = dueDateC.concat([PosX, PosY]);
        dueDateC = formatCoords(dueDateC);
        nDueDateC = normalizeCoords(dueDateC);
        drawRect('selddate', dueDateC);
        document.getElementById('selddate').setAttribute('data-page', currPage);
        // fill hidden fields
        updateHiddenInput('due_date', currPage, nDueDateC[0], nDueDateC[1], nDueDateC[4], nDueDateC[5]);

    } else if (tempActField.id == 'amtdue') {
        $('#amtdueicon').removeClass('edit');
        $('#amtdueicon').addClass('check circle outline');
        amtDueC = amtDueC.concat([PosX, PosY]);
        amtDueC = formatCoords(amtDueC);
        nAmtDueC = normalizeCoords(amtDueC);
        drawRect('selamtdue', amtDueC);
        document.getElementById('selamtdue').setAttribute('data-page', currPage);
        // fill hidden fields
        updateHiddenInput('amount', currPage, nAmtDueC[0], nAmtDueC[1], nAmtDueC[4], nAmtDueC[5]);

    } else {
        displayError("Please click on an item below before selecting");
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
        $('#issuedateicon').removeClass('check circle outline');
        $('#issuedateicon').addClass('green edit');
        tempCoords = issueDateC.concat([PosX, PosY]);
        tempCoords = formatCoords(tempCoords);
        drawRect('selidate', tempCoords);
    } else if (tempActField.id == 'period') {
        $('#rperiodicon').removeClass('grey');
        $('#rperiodicon').removeClass('check circle outline');
        $('#rperiodicon').addClass('green edit');
        tempCoords = recPeriodC.concat([PosX, PosY]);
        tempCoords = formatCoords(tempCoords);
        drawRect('selrperiod', tempCoords);
    } else if (tempActField.id == 'duedate') {
        $('#duedateicon').removeClass('grey');
        $('#duedateicon').removeClass('check circle outline');
        $('#duedateicon').addClass('green edit');
        tempCoords = dueDateC.concat([PosX, PosY]);
        tempCoords = formatCoords(tempCoords);
        drawRect('selddate', tempCoords);
    } else if (tempActField.id == 'amtdue') {
        $('#amtdueicon').removeClass('grey');
        $('#amtdueicon').removeClass('check circle outline');
        $('#amtdueicon').addClass('green edit');
        tempCoords = amtDueC.concat([PosX, PosY]);
        tempCoords = formatCoords(tempCoords);
        drawRect('selamtdue', tempCoords);
    } else {
        displayError("Please click on an item below before selecting");
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
        } else if (tempActField.id == 'period') {
            $('#rperiodicon').removeClass('edit');
            $('#rperiodicon').addClass('check circle outline');
            recPeriodC = recPeriodC.concat([PosX, PosY]);
        } else if (tempActField.id == 'duedate') {
            $('#duedateicon').removeClass('edit');
            $('#duedateicon').addClass('check circle outline');
            dueDateC = dueDateC.concat([PosX, PosY]);
        } else if (tempActField.id == 'amtdue') {
            $('#amtdueicon').removeClass('edit');
            $('#amtdueicon').addClass('check circle outline');
            amtDueC = amtDueC.concat([PosX, PosY]);
        }

        //document.getElementById('temp').innerHTML = "Image size: " + billsize +
        //                                            "<br>" + "mouseupped outside image at: " + PosX + ", " + PosY;
    }
    selecting = false;
}
