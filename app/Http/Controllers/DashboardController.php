<?php

namespace App\Http\Controllers;

use App\BillingOrganization;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $bill_orgs = BillingOrganization::all();

        return view('dashboard.index', compact('bill_orgs'));
    }
}
