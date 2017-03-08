<?php

namespace App\Http\Controllers;

use App\RecordIssuerType;
use App\Record;
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

    public function show(Record $record) {
        $this->authorize('belongs_to_user', $record);

        // need to prepend 'app/' because Storage::url is stupid. It returns storage/ instead of storage/app/
        $url = storage_path('app/' . $record->path_to_file);

        return response()->file($url);
    }


    public function download(Record $record) {
        $this->authorize('belongs_to_user', $record);

        // need to prepend 'app/' because Storage::url is stupid. It returns storage/ instead of storage/app/
        $url = storage_path('app/' . $record->path_to_file);
        $url_parts = pathinfo($url);
        $file_name = $url_parts['filename'] . '.' . $url_parts['extension'];

        return response()->download($url, $file_name);
    }

    public function destroy(Record $record) {
        $this->authorize('belongs_to_user', $record);

        // TODO: handle deletion failure
        $deletion_success = Storage::delete($record->path_to_file);
        $record->delete();

        return back();
    }

    public function edit(Record $record)
    {
        /*
         $is_issuer_type_bill is
         used to determine whether to hide bill related form controls in views.
         e.g only bills have due date but not bank statements.
         */
        $is_issuer_type_bill = $record->is_issuer_type_bill();
        return view('records.edit', compact('record', 'is_issuer_type_bill'));
    }

    public function update(Record $record)
    {
        $request_all = request()->all();
        $user_id = auth()->id();
        $issue_date = request('issue_date');
        $issuer_name = $record->issuer_name();
        $path_of_uploaded_file = $record->path_to_file;

        if (request()->file('record_file')) // if user optionally uploaded a file
        {
            $file = request()->file('record_file');
            $extension = $file->extension();
            $file_name = $issuer_name . $issue_date . '.'. $extension;
            $path_of_uploaded_file = $file->storeAs('records'.$user_id, $file_name, ['visibility'=>'private']);
        }

        // build variable list to update

        $field_list = array_merge(request(['issue_date', 'due_date']), [
            'period' => request('record_period'),
            'path_to_file' => $path_of_uploaded_file,
            'amount' => request('amount_due'),
        ]);
        $field_list =array_filter($field_list); // filter the fields user do not want update
        $record->update($field_list);
        return back();
    }
}
