@extends('layouts.error')

@section('error_code')
    405
@endsection

@section('error_message')
    Whoops! You're not allowed to do this!
@endsection

@section('error_instruction')
    <p>You can go back to the <a href="javascript:history.go(-1)">previous page</a> or our <a href="/">homepage</a></p>
@endsection

@section('error_image')
    <img id="error-hero" src="/images/elegantowl.jpg" alt="Owl">
    <p><a href='http://www.freepik.com/free-vector/hand-drawn-owl_1089571.htm'>Designed by Freepik</a></p>
@endsection