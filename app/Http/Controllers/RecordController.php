<?php

namespace App\Http\Controllers;

use App\RecordIssuerType;
use App\Record;
use App\UserRecordIssuer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public static $record_issuer_types;

    public function __construct() {
        $this->middleware('auth');

        // TODO: extract this somewhere else (used in USerRecordIssuerController too!)
        // Create an assoc. array of id => type
        $record_issuer_types = RecordIssuerType::all();
        foreach ($record_issuer_types as $record_issuer_type) {
            self::$record_issuer_types[$record_issuer_type->id] = $record_issuer_type->type;
        }

    }

    // TODO: clean up this mess if possible?
    public function store(UserRecordIssuer $record_issuer) {
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

    public function show(UserRecordIssuer $record_issuer, Record $record) {
        // TODO: ensure that user is authorized to view
        // need to prepend 'app/' because Storage::url is stupid. It returns storage/ instead of storage/app/
        $url = storage_path('app/' . $record->path_to_file);

        return response()->file($url);
    }
}
