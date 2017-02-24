<?php

namespace App\Http\Controllers;

use App\RecordIssuerType;
use App\Record;
use App\UserRecordIssuer;
use Illuminate\Support\Facades\DB;
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


    // TODO: clean up this mess if possible?
    public function store_record(UserRecordIssuer $record_issuer) {
        // TODO: authorization fails -> move to UserRecordPolicy
        $this->authorize('belongs_to_user', $record_issuer);

        // Date format received: YYYY-MM-DD
        $this->validate(request(), [
            'record' => 'required',
            'issue_date' => 'required',
            'period' => 'required',
            'amount' => 'required'
        ]);

        if (self::$record_issuer_types[$record_issuer->type] === 'billing organization') {
            $this->validate(request(), [
                'due_date' => 'required'
            ]);
        }

        $user_id = auth()->id();
        $file_extension = request()->file('record')->extension();
        $file_name = $record_issuer->name . request('issue_date') . '.' . $file_extension;
        $path = request()->file('record')
            ->storeAs('records/' . $user_id, $file_name, ['visibility' => 'private']);
        // research on visibility public vs private -> currently there's not a lot of documentation on this
        $period = request('period') . '-01';    // YYYY-MM -> YYYY-MM-01

        $db_connection_name = DB::connection()->getName();
        // In MySQL, the function is called STR_TO_DATE, in postgres (and others -- check it out), TO_DATE
        $to_date_fname = $db_connection_name === 'mysql' ? 'STR_TO_DATE' : 'TO_DATE';
        $date_format = $db_connection_name === 'mysql' ? '%Y-%m-%d' : 'YYYY-MM-DD';
        $pdo = DB::connection()->getPdo();

        $quoted_period = $pdo->quote($period);   // to protect against SQL injection
        $quoted_date_format = $pdo->quote($date_format);

        auth()->user()->create_record(
            new Record(
                request(['issue_date', 'due_date', 'amount']) + [
                    'period' => DB::raw($to_date_fname . '(' . $quoted_period . ', ' . $quoted_date_format . ')'),
                    'path_to_file' => $path,
                    'user_record_issuer_id' => $record_issuer->id
                ]
            )
        );

        return back();
    }
}
