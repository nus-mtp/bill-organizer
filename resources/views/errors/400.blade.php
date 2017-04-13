@extends('layouts.error')

@section('error_code')
    400
@endsection

@section('error_message')
    Whoops! That's a bad request!
@endsection

@section('error_instruction')
    <p>You can go back to the <a href="javascript:history.go(-1)">previous page</a> or our <a href="/">homepage</a></p>
@endsection

@section('error_image')
    <img id="error-hero" src="/images/elegantowl.jpg" alt="Owl">
    <p><a href='http://www.freepik.com/free-vector/hand-drawn-owl_1089571.htm'>Designed by Freepik</a></p>
@endsection