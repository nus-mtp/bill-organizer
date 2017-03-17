<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Faker\Provider\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Image\ImageEditor;
use App\RecordIssuerType;
use App\Record;
use App\RecordIssuer;
use App\TempRecord;
use App\TempRecordPage;

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

        $saved_record = auth()->user()->create_record(
            new Record(
                request(['issue_date', 'due_date', 'amount', 'period']) + [
                    'record_issuer_id' => $record_issuer->id
                ]
            )
        );

        // TODO: extract these to FileHandler
        $file_extension = request()->file('record')->extension();
        $file_name = "{$saved_record->id}.{$file_extension}";
        $dir_path = "users/{$user_id}/record_issuers/{$record_issuer->id}/records";
        $path = request()->file('record')
            ->storeAs($dir_path, $file_name, ['visibility' => 'private']);
        // research on visibility public vs private -> currently there's not a lot of documentation on this

        $saved_record->update([
            'path_to_file' => $path
        ]);

        return back();
    }

    /**
     * Handles file upload and direct to the coordinates extraction page
     */
    public function store_temp_record(RecordIssuer $record_issuer) {
        // authorize
        $this->authorize('belongs_to_user', $record_issuer);

        // validate or redirect
        $this->validate(request(), [
            'record' => 'required'
        ]);

        // store somewhere
        $user_id = auth()->id();
        $file_extension = request()->file('record')->extension();
        $file_name = Carbon::now()->timestamp . ".{$file_extension}";
        $dir_path = "tmp/users/{$user_id}/record_issuers/{$record_issuer->id}/records";
        $path = request()->file('record')
            ->storeAs($dir_path, $file_name, ['visibility' => 'private']);

        $saved_temp_record = auth()->user()->create_temp_record(
            new TempRecord([
                'record_issuer_id' => $record_issuer->id,
                'path_to_file' => $path
            ])
        );

        // convert pdf to images and store somewhere
        $temp_images_dir_path = "tmp/users/{$user_id}/record_issuers/{$record_issuer->id}/records/" .
            "{$saved_temp_record->id}/img/";
        if(!Storage::exists($temp_images_dir_path)) {
            Storage::makeDirectory($temp_images_dir_path, 0777, true, true);
        }

        // TODO: In dire need of a FileHandler that'll return path relative to storage and full path!!
        $num_pages = ImageEditor::getPdfNumPages(storage_path('app/' . $path));
        for ($i = 0; $i < $num_pages; $i++) {
            $file_name = "{$i}.jpg";

            // need to append 'app/' Is this a bug in Laravel??? Cannot use Storage::url and storage_path just return dir up to storage
            ImageEditor::jpegFromPdf(storage_path('app/' . $path), $i, storage_path('app/' . $temp_images_dir_path . $file_name));

            $saved_temp_record->pages()->save(
                new TempRecordPage([
                    'path' => $temp_images_dir_path . $file_name
                ])
            );
        }

        return redirect()->route('create_temp_record', $saved_temp_record);
    }
}
