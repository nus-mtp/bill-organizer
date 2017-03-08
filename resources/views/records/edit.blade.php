@extends('layouts.app')

@section('content')
<div class="ui container">

    <form class="ui form" action=" {{ route('update_record', $record)}}" enctype="multipart/form-data">

        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        <h3>Update</h3>

        <div class="field">
            <label for='amount'>Amount Due:</label>
            <input name="amount" type="text" placeholder="{{ $record->amount }}"></input>
        </div>

        <div class="two fields">
            <div class="field">
                <label for='date'>Issue Date:</label>
                <div class="ui calendar">
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input name="date" type="text" placeholder="{{ $record->issue_date->format('d/m/Y') }}">
                     </div>
                </div>
            </div>

            @if($is_issuer_type_bill)
            <div class="field">
                <label for='due_date'>Due Date:</label>
                <div class="ui calendar">
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input name="due_date" type="text" placeholder="{{ $record->due_date->format('d/m/Y') }}">
                    </div>
                </div>
            </div>
            @endif

        </div> <!-- end of two fields -->

        <div class="field">
            <label for='month'>Record Period:</label>
            <div class="ui calendar-month field">
                <div class="ui input left icon">
                    <i class="calendar icon"></i>
                    <input name="month" type="text" placeholder="{{ $record->period->format('M/Y')}}"></input>
                 </div>
            </div>
        </div>


        <div class="field">
            <label for="record">Choose a new record</label>
            <div class="ui action input left icon">
                <i class="file icon"></i>
                <input type="file" class='input-file' name="record" id="record"></input>
                <a href="{{ route('show_record_file', $record) }}" class="ui green button" role = 'button'>View Existing File </a>
            </div>
        </div>

        <div class="preview area">
            {{--  <h3>Uploaded File Preview Area Replace this with dropzone</h3> --}}
        </div>

        <button class="ui green button" type="submit"> Submit </button>

    </form>

</div>

@endsection
