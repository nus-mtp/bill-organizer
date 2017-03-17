<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\RecordIssuerType;
use App\Record;
use App\RecordIssuer;

class RecordIssuerController extends Controller
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

    public function show(RecordIssuer $record_issuer) {
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
            new RecordIssuer(request(['name', 'type']))
        );

        return back();
    }

    public function destroy(RecordIssuer $record_issuer) {
        $this->authorize('belongs_to_user', $record_issuer);

        // TODO: extract these constants. It's not a good practice to refer to the same string literal everywhere
        DB::table('records')->where('record_issuer_id', $record_issuer->id)->delete();
        $record_issuer->delete();

        return back();
    }


    // TODO: clean up this mess if possible?
    /**
     * Store_record is here because it needs to be validated that the RecordIssuer belongs to the current user
     */
    public function store_record(RecordIssuer $record_issuer) {
        // only if this record_issuer belongs to me can I add a new record. I shouldn't be able to add to other user's record issuer
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
        $file_name = $record_issuer->name . '_' . request('issue_date') . '.' . $file_extension;
        $dir_path = "/users/{$user_id}/record_issuers/{$record_issuer->id}/records/";
        $path = request()->file('record')
            ->storeAs($dir_path, $file_name, ['visibility' => 'private']);
        // research on visibility public vs private -> currently there's not a lot of documentation on this

        auth()->user()->create_record(
            new Record(
                request(['issue_date', 'due_date', 'amount', 'period']) + [
                    'path_to_file' => $path,
                    'record_issuer_id' => $record_issuer->id
                ]
            )
        );

        return back();
    }
}
