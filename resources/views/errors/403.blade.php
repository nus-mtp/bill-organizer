@extends('layouts.error')

@section('error_code')
  403
@endsection

@section('error_message')
  YOU SHALL NOT PASS
@endsection

@section('error_instruction')
  <p>You heard the old guy! You can go back to the <a href="javascript:history.go(-1)">previous page</a> or our <a href="/">homepage</a></p>
@endsection

@section('error_image')
  <img id="error-hero" src="https://i.giphy.com/njYrp176NQsHS.gif" alt="Gandalf: you shall not pass">
@endsection