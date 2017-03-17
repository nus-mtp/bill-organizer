<?php

namespace App\Http\Controllers;

use App\RecordIssuerType;
use App\RecordIssuer;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $record_issuers = auth()->user()->record_issuers;
        $record_issuer_types = RecordIssuerType::all();

        return view('dashboard.index', compact('record_issuers', 'record_issuer_types'));
    }
}
