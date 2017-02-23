<?php

namespace App\Http\Controllers;

use App\RecordIssuerType;
use App\UserRecordIssuer;
use Illuminate\Http\Request;

class UserRecordIssuerController extends Controller
{
    public static $record_issuer_types;

    public function __construct() {
        // Create an assoc. array of id => type
        $record_issuer_types = RecordIssuerType::all();
        foreach ($record_issuer_types as $record_issuer_type) {
            self::$record_issuer_types[$record_issuer_type->id] = $record_issuer_type->type;
        }

        $this->middleware('auth');
    }

    public function show(UserRecordIssuer $record_issuer) {
        $this->authorize('belongs_to_user', $record_issuer);

        $records = $record_issuer->records;
        $type = self::$record_issuer_types[$record_issuer->type]; // $record_issuer type is an ID
        $amount_field_name = $type === 'bank' ? 'Balance' : 'Amount due';

        return view('dashboard.record-issuer', compact('record_issuer', 'records', 'type', 'amount_field_name'));
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
        $this->authorize('belongs_to_user', $record_issuer);

        $record_issuer->delete();

        return back();
    }
}
