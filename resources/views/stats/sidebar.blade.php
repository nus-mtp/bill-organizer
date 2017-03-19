<!--SIDEBAR-->
<div class="four wide column left-bordered" id="stats">

    <h4>Statistics for this year</h4>

    <form action="/stats/{{$record_issuer->id}}" id="stats-form" method="get">
        <select name= 'stats-options' class="ui fluid s dropdown" id ='js-stats-menu'>
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

            <div class="red statistic js-bill-total">
                <div class="value"></div>
                <div class="label">Spent</div>
            </div>


        <div class="l-charts">
            <canvas id="amountBarChart" width="400" height="500">
            </canvas>
        </div>


    </div>
</div>
