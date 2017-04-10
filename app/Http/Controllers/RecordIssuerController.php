<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Faker\Provider\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Helpers\StorageHelper;
use App\Image\ImageEditor;
use App\RecordIssuerType;
use App\Record;
use App\RecordPage;
use App\RecordIssuer;

class RecordIssuerController extends Controller
{
    public static $record_issuer_types;

    public function __construct() {
        // an assoc. array of id => type
        $record_issuer_types = RecordIssuerType::idToType();

        $this->middleware('auth');
    }

    public function show(RecordIssuer $record_issuer) {
        $this->authorize('belongs_to_user', $record_issuer);

        $records = $record_issuer->records()->temporary(false)->get();
        $type = self::$record_issuer_types[$record_issuer->type]; // $record_issuer type is an ID
        $amount_field_name = $type === 'bank' ? 'Balance' : 'Amount due';

        return view('dashboard.record-issuer', compact('record_issuer', 'records', 'type', 'amount_field_name'));
    }

    public function store() {
        // TODO:
        // Validate the type -- research on Validator
        // $record_issuer_types = RecordIssuerType::pluck('id');

        $this->validate(request(), [
            'name' => 'required|max:191',
            'type' => 'required'
        ]);

        auth()->user()->create_record_issuer(
            new RecordIssuer(request(['name', 'type']))
        );

        return back();
    }

    public function destroy(RecordIssuer $record_issuer) {
        $this->authorize('belongs_to_user', $record_issuer);

        $record_issuer->delete();

        return back();
    }

    /**
     * Handles file upload and direct to the coordinates extraction page
     */
    public function upload_record_file(RecordIssuer $record_issuer) {

        // authorize
        $this->authorize('belongs_to_user', $record_issuer);

        // validate or redirect
        // TODO: validate isPDF
        $this->validate(request(), [
            'record' => 'required'
        ]);

        $path = StorageHelper::storeUploadedRecordFile(request()->file('record'), $record_issuer);
        $saved_record = auth()->user()->create_record(
            // use template from record_issuer immediately if exists
            new Record([
                'template_id' => $record_issuer->active_template() ? $record_issuer->active_template()->id : null,
                'temporary' => true,
                'record_issuer_id' => $record_issuer->id,
                'path_to_file' => $path
            ])
        );

        // convert pdf to images and store
        $record_images_dir_path = StorageHelper::createRecordImagesDir($saved_record);

        $full_path = StorageHelper::getAbsolutePath($path);
        $num_pages = ImageEditor::getPdfNumPages($full_path);
        for ($i = 0; $i < $num_pages; $i++) {
            $file_name = "{$i}.jpg";
            $page_path = $record_images_dir_path . $file_name;

            // need to append 'app/' Is this a bug in Laravel??? Cannot use Storage::url and storage_path just return dir up to storage
            ImageEditor::jpegFromPdf($full_path, $i, StorageHelper::getAbsolutePath($page_path));

            $saved_record->pages()->save(
                new RecordPage([
                    'path' => $page_path
                ])
            );
        }

        return redirect()->route('add_template', $saved_record);
    }
}
