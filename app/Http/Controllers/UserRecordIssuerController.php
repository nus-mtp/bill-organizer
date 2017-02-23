<?php

namespace App\Http\Controllers;

use App\RecordIssuerType;
use App\UserRecordIssuer;
use Illuminate\Http\Request;

class UserRecordIssuerController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function show(UserRecordIssuer $record_issuer) {
        $records = $record_issuer->records;

        return view('dashboard.record-issuer', compact('record_issuer', 'records'));
    }

    public function store() {
        // TODO: determine if should add max len constraint?
        // Validate the type -- research on Validator
        // $record_issuer_types = RecordIssuerType::pluck('id');

        $this->validate(request(), [
            'name' => 'required',
            'type' => 'required'
        ]);

        auth()->user()->create_record_issuer(
            new UserRecordIssuer(request(['name', 'type']))
        );

        return back();
    }

    public function destroy(UserRecordIssuer $record_issuer) {
        $this->authorize('delete', $record_issuer);

        $record_issuer->delete();

        return back();
    }
}
