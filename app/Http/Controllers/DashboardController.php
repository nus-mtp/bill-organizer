<?php

namespace App\Http\Controllers;

use App\UserRecordIssuer;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $user_record_issuers = auth()->user()->record_issuers;

        return view('dashboard.index', compact('user_record_issuers'));
    }
}
