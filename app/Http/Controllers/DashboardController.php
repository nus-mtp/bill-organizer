<?php

namespace App\Http\Controllers;

use App\RecordIssuerType;
use App\UserRecordIssuer;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $user_record_issuers = auth()->user()->record_issuers;
        $record_issuer_types = RecordIssuerType::all();

        return view('dashboard.index', compact('user_record_issuers', 'record_issuer_types'));
    }
}
