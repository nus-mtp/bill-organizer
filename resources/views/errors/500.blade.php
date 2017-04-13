@extends('layouts.error')

@section('error_code')
  500
@endsection

@section('error_message')
  Whoops! We're having a technical difficulty here! Sorry for the inconvenience!
@endsection

@section('error_instruction')
  <p>You can go back to the <a href="javascript:history.go(-1)">previous page</a> or our <a href="/">homepage</a>.
    If you keep encountering this error, please kindly send us an email
    <a href="mailto:hartantoteddy@u.nus.edu?Subject=Owlganizer%20bug" target="_top">here</a> and describe your
    situation.
  </p>
@endsection

@section('error_image')
  <img id="error-hero" src="/images/elegantowl.jpg" alt="Owl">
  <p><a href='http://www.freepik.com/free-vector/hand-drawn-owl_1089571.htm'>Designed by Freepik</a></p>
@endsection