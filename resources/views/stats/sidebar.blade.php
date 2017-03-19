<!--SIDEBAR-->
<div class="four wide column" id="stats"
     style="height: 100vh; border-left:1px #ccc solid; display:block; text-align:center;">
    <h4>Statistics for this year</h4>

    <form action="/stats/{{$record_issuer->id}}" id="stats-form" method="get">
        <select name= 'stats-options' class="ui dropdown" id ='js-stats-menu'>
            <option class="item selected" value="0">This Month</option>
            <option class="item" value="6">past 6 months</option>
            <option class="item" value="12">This year</option>
            <option class="item" value="24">Past 2 years</option>
            <option class="item" value="99999">All of time</option>
        </select>
    </form>



    <div class="ui horizontal statistics js-stats-container" >

        <div class="red statistic js-bill-count" >
            <div class="value"></div>
            <div class="label">bills</div>
        </div>

        <div class="red statistic js-bill-amount">
            <div class="value"></div>
            <div class="label">Spent</div>
        </div>

    </div>
</div>