@extends('layouts.app')

@section('content')

    <!--CONTENT-->
    <div class="ui main container" style="padding:90px 65px 65px 65px; min-height: 100vh;">

    <!--statistics sidebar-->
        <div class="ui visible sidebar inverted vertical right menu"
             style="padding-top: 70px;">
            <div class="ui segment">
              <div class="statistic">
                  <div class="value">
                      <h2>$11</h2>
                  </div>
                  <div class="label">
                      avg per month
                  </div>
                </div>
            </div>
        </div>
  <div class="pusher">
    <!-- Site content !-->        
        
        <div class="ui container">
            <div class="ui grid">
                <div class="sixteen wide column">
                    <div class="ui breadcrumb">
                        <a class="section" href="/">Home</a>
                        <i class="right angle icon divider"></i>
                        <a class="section" href="/dashboard">Dashboard</a>
                        <i class="right angle icon divider"></i>
                        <div class="active section">Sample Organization</div>
                    </div>
                </div>
                <div class="sixteen wide column">
                    <h1>Sample Organization - Bills</h1>
                    <!--if no billing organisations in db-->
                    <div class="ui tiny warning message">
                        <p>There are no records yet - start by adding one below! (ﾉ^ヮ^)ﾉ*:・ﾟ✧</p>
                    </div>
                </div>
                <div class="sixteen wide column">
                    <!--sorting not working yet; needs javascript-->
                    <table class="ui sortable celled table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" name="example"></th>
                                <th class="sorted ascending">Name</th>
                                <th class="sorted ascending">Billing Month</th>
                                <th class="sorted ascending">Amount</th>
                                <th class="sorted ascending">Status</th>
                            </tr>
                        </thead>
                        <tbody>                            
                            <tr>
                                <td><input type="checkbox" name="example"></td>
                                <td>2017-03-01</td>
                                <td>2017 February</td>
                                <td>$2</td>
                                <td class="warning">Not paid</td>
                            </tr>
                                    
                            <tr>
                                <td><input type="checkbox" name="example"></td>
                                <td>2017-02-04</td>
                                <td>2017 January</td>
                                <td>$30</td>
                                <td class="negative">Overdue</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="example"></td>
                                <td>2017-01-03</td>
                                <td>2016 December</td>
                                <td>$0</td>
                                <td class="positive">Paid</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="ui blue right floated button" onclick="$('.ui.sidebar').sidebar('toggle')
;">Stats</div>
                </div>
            </div>

            <div class="ui small modal">
                <i class="close icon"></i>
                <div class="header">Add new billing organisation</div>
                <div class="content">
                    <div class="ui fluid icon input">
                        <input type="text" placeholder="Enter billing organisation name">
                    </div>
                </div>
                <div class="actions">
                    <div class="ui button approve green" data-value="yes">Add</div>
                    <div class="ui button cancel" data-value="no">Cancel</div>
                </div>
            </div>
      </div></div><!--end of sidebar-->
    </div>
@endsection