<?php

namespace App\Http\Controllers;

use App\RecordIssuerType;
use App\Record;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateRecordForm;
use League\Flysystem\Exception;

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

    public function update(UpdateRecordForm $request, Record $record)

    {
        // add Gate:: here, allow(some policy) if auth()-id() === post(id) : allow else deny
        $path_of_uploaded_file = $record->path_to_file;
        $this->upload_file($request, $record);

        $field_list = array(
            'due_date'     => $request->issue_date,
            'issue_date'   => $request->due_date,
            'period'       => $request->period,
            'path_to_file' => $path_of_uploaded_file,
            'amount'       => $request->amount,
        );

        $record->update($this->$field_list);
        return back();
    }

    private function upload_file($request, $record){
        // upload only if user optionally uploaded a file
        if ($request ->file('record_file')){
            $file          = $request->file('record_file');
            $extension     = $file->extension();
            $file_name     = $record->issuer_name() . $request->issue_date . '.'. $extension;
            $path_to_store = 'records'.$record->user_id;

            return $path_of_uploaded_file = $file->storeAs($path_to_store, $file_name, ['visibility'=>'private']);
        }
        return null;
    }
}
