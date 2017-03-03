
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
                    <div class="dotted-container">
                        Preview image here
                    </div>
                </div>
                
                <div class="eight wide column">
                    <div class="ui form">
                        <div class="field">
                            <label>Issue Date</label>
                            <input type="date" name="first-name" placeholder="First Name">
                        </div>
                        <div class="field">
                            <label>Record Period</label>
                            <input type="month" name="last-name" placeholder="Last Name">
                        </div>
                        <div class="field">
                            <label>Due Date</label>
                            <input type="date" name="last-name" placeholder="Last Name">
                        </div>
                        <div class="field">
                            <label>Amount Due</label>
                            <input type="number" name="amt-due" placeholder="e.g 400">
                        </div>
                        <button class="ui positive button" type="submit">Submit</button>
                        <button class="ui black button" type="cancel">Cancel</button>
                    </div>
                </div>
            </div>
</div>
@endsection