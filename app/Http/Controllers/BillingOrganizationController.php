<?php

namespace App\Http\Controllers;

use App\BillingOrganization;
use Illuminate\Http\Request;

class BillingOrganizationController extends Controller
{
    public function store() {
        // TODO: determine if should add max len constraint?
        $this->validate(request(), ['name' => 'required']);

        BillingOrganization::create(request(['name']));

        return back();
    }
}
