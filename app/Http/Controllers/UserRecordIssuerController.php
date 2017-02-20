<?php

namespace App\Http\Controllers;

use App\UserRecordIssuer;
use Illuminate\Http\Request;

class UserRecordIssuerController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function store() {
        // TODO: determine if should add max len constraint?
        $this->validate(request(), ['name' => 'required']);

        auth()->user()->create_record_issuer(
            new UserRecordIssuer(request(['name']))
        );

        return back();
    }

    public function destroy(UserRecordIssuer $record_issuer) {
        $record_issuer->delete();

        return back();
    }
}
