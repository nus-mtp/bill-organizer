
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
                        <img src="{{url('placeholderbill.jpg')}}" style="width:100%;" id="bill" onmousedown="getCoordinates(event)">
                    </div>
                </div>
                
                <div class="eight wide column">
                    <div class="ui message" id="temp">for test</div>
                    <div class="ui form">
                        <div class="field">
                            <label>Issue Date</label>
                            <input type="text" name="issuedate" placeholder="Issue Date" id="issue" value="what">
                        </div>
                        <div class="field">
                            <label>Record Period</label>
                            <input type="text" name="recordperiod" placeholder="Record Period">
                        </div>
                        <div class="field">
                            <label>Due Date</label>
                            <input type="text" name="duedate" placeholder="Due Date">
                        </div>
                        <div class="field">
                            <label>Amount Due</label>
                            <input type="text" name="amt-due" placeholder="e.g 400">
                        </div>
                        <button class="ui positive button" type="submit">Submit</button>
                        <button class="ui black button" type="cancel">Cancel</button>
                    </div>
                </div>
            </div>
</div>

<script type="text/javascript">
    
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
    
    function getCoordinates(e){
        var PosX = 0;
        var PosY = 0;
        var ImgPos;
        var billImg = document.getElementById('bill');
        ImgPos = FindPosition(billImg);
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
        document.getElementById("temp").innerHTML = PosX + ", " + PosY;
        document.activeElement.value = PosX + ", " + PosY;
    }

</script>
@endsection