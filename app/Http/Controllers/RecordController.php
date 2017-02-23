<?php

namespace App\Http\Controllers;

use App\UserRecordIssuer;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function store(UserRecordIssuer $record_issuer) {
        dd(request());
    }
}
