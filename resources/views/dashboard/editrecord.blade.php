
@extends('layouts.app')

@section('content')
<!--CONTENT-->
<div class="ui fluid container">
    <div class="ui grid">
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
                
                <div class="eight wide column">
                    <div class="bill-image">
                        <div class="selRect" id="selidate"></div>
                        <div class="selRect" id="selrperiod"></div>
                        <div class="selRect" id="selddate"></div>
                        <div class="selRect" id="selamtdue"></div>
                        <img src="{{url('placeholderbill.jpg')}}" style="width:100%;" id="bill" onmousedown="getCoordinates(event)" onmouseup="getCoordsAgain(event)" onmouseout="coordsFailSafe(event)">
                        
                    </div>
                </div>
                
                <div class="eight wide column">
                    for testing
                    <div class="ui message" id="temp">for test</div>
                    <div class="ui form">
                        <div class="field">
                            <label>Issue Date</label>
                            <input type="text" name="issuedate" placeholder="Issue Date" id="issue">
                        </div>
                        <div class="field">
                            <label>Record Period</label>
                            <input type="text" name="recordperiod" placeholder="Record Period" id="period">
                        </div>
                        <div class="field">
                            <label>Due Date</label>
                            <input type="text" name="duedate" placeholder="Due Date" id="duedate">
                        </div>
                        <div class="field">
                            <label>Amount Due</label>
                            <input type="text" name="amt-due" placeholder="e.g 400" id="amtdue">
                        </div>
                        <button class="ui positive button" type="submit">Submit</button>
                        <button class="ui black button" type="cancel">Cancel</button>
                    </div>
                </div>
            </div>
</div>

<script type="text/javascript">
    
    // disable default image drag action so you can drag select box later
    document.getElementById('bill').ondragstart = function(event) { event.preventDefault(); };
    
    var issueDateC;
    var recPeriodC;
    var dueDateC;
    var amtDueC;
    var billImg = document.getElementById('bill');
    var tempActField;
    var selecting = false;
    
    function drawRect(box, coords){
        document.getElementById(box).style.left = coords[0] +'px';
        document.getElementById(box).style.top = coords[1] +'px';
        document.getElementById(box).style.width = (coords[2]-coords[0]) +'px';
        document.getElementById(box).style.height = (coords[3]-coords[1]) +'px';
    }
    
    function FindPosition(oElement)
    {
        if(typeof( oElement.offsetParent ) != "undefined") {
            for(var posX = 0, posY = 0; oElement; oElement = oElement.offsetParent) {
                posX += oElement.offsetLeft;
                posY += oElement.offsetTop;
            }
            return [ posX, posY ];
        }
        else {
            return [ oElement.x, oElement.y ];
        }
    }
    
    var ImgPos = FindPosition(billImg);
    
    function getCoordinates(e){
        var PosX = 0;
        var PosY = 0;
        if (!e) var e = window.event;
        if (e.pageX || e.pageY) {
            PosX = e.pageX;
            PosY = e.pageY;
        }
        else if (e.clientX || e.clientY) {
            PosX = e.clientX + document.body.scrollLeft
                + document.documentElement.scrollLeft;
            PosY = e.clientY + document.body.scrollTop
                + document.documentElement.scrollTop;
        }
        PosX = PosX - ImgPos[0];
        PosY = PosY - ImgPos[1];
        var activeField = document.activeElement;
        tempActField = activeField;
        activeField.value = PosX + ", " + PosY;
        
        if(document.activeElement.id == 'issue'){
            issueDateC = [PosX, PosY];
            document.getElementById("temp").innerHTML = "Issue Date: " + issueDateC[0] + ", " + issueDateC[1];
        }
        else if(document.activeElement.id == 'period'){
            recPeriodC = [PosX, PosY];
            document.getElementById("temp").innerHTML = "Record Period: " + recPeriodC[0] + ", " + recPeriodC[1];
        }
        else if(document.activeElement.id == 'duedate'){
            dueDateC = [PosX, PosY];
            document.getElementById("temp").innerHTML = "Due Date: " + dueDateC[0] + ", " + dueDateC[1];
        }
        else if(document.activeElement.id == 'amtdue'){
            amtDueC = [PosX, PosY];
            document.getElementById("temp").innerHTML = "Amount Due: " + amtDueC[0] + ", " + amtDueC[1];
        }
        selecting = true;
    }
    
    function getCoordsAgain(e){
        var PosX = 0;
        var PosY = 0;
        if (!e) var e = window.event;
        if (e.pageX || e.pageY) {
            PosX = e.pageX;
            PosY = e.pageY;
        }
        else if (e.clientX || e.clientY) {
            PosX = e.clientX + document.body.scrollLeft
                + document.documentElement.scrollLeft;
            PosY = e.clientY + document.body.scrollTop
                + document.documentElement.scrollTop;
        }
        PosX = PosX - ImgPos[0];
        PosY = PosY - ImgPos[1];
        
        if(tempActField.id == 'issue'){
            issueDateC = issueDateC.concat([PosX, PosY]);
            drawRect('selidate',issueDateC);
            document.getElementById("temp").innerHTML = "Issue Date: " + issueDateC;
        }
        else if(tempActField.id == 'period'){
            recPeriodC = recPeriodC.concat([PosX, PosY]);
            drawRect('selrperiod',recPeriodC);
            document.getElementById("temp").innerHTML = "Record Period: " + recPeriodC;
        }
        else if(tempActField.id == 'duedate'){
            dueDateC = dueDateC.concat([PosX, PosY]);
            drawRect('selddate',dueDateC);
            document.getElementById("temp").innerHTML = "Due Date: " + dueDateC;
        }
        else if(tempActField.id == 'amtdue'){
            amtDueC = amtDueC.concat([PosX, PosY]);
            drawRect('selamtdue',amtDueC);
            document.getElementById("temp").innerHTML = "Amount Due: " + amtDueC;
        }
        else{
            document.getElementById("temp").innerHTML = "nothing selected";
        }
        selecting = false;
    }
    
    function coordsFailSafe(e){        
        if(selecting){
            var PosX = 0;
            var PosY = 0;
            if (!e) var e = window.event;
            if (e.pageX || e.pageY) {
                PosX = e.pageX;
                PosY = e.pageY;
            }
            else if (e.clientX || e.clientY) {
                PosX = e.clientX + document.body.scrollLeft
                    + document.documentElement.scrollLeft;
                PosY = e.clientY + document.body.scrollTop
                    + document.documentElement.scrollTop;
            }
            PosX = PosX - ImgPos[0];
            PosY = PosY - ImgPos[1];
            
            var billsize = [document.getElementById('bill').width, document.getElementById('bill').height];
            
            //adjust coordinates to fit in image
            if(PosX > billsize[0]) {
                PosX = billsize[0]; 
            }
            if(PosY > billsize[1]) {
               PosY = billsize[1];
            }
            else if(PosX < 0) {
                PosX = 0;
            }
            else if(PosY < 0) {
                PosY = 0;
            }
            
            if(tempActField.id == 'issue'){
                issueDateC = issueDateC.concat([PosX, PosY]);
                document.getElementById("temp").innerHTML = "Image size: " + billsize +
                                                        "<br>" + "Issue Date: " + issueDateC;
            }
            else if(tempActField.id == 'period'){
                recPeriodC = recPeriodC.concat([PosX, PosY]);
                document.getElementById("temp").innerHTML = "Record Period: " + recPeriodC;
            }
            else if(tempActField.id == 'duedate'){
                dueDateC = dueDateC.concat([PosX, PosY]);
                document.getElementById("temp").innerHTML = "Due Date: " + dueDateC;
            }
            else if(tempActField.id == 'amtdue'){
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