<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateRecordForm;
use League\Flysystem\Exception;

use App\Helpers\StorageHelper;
use App\Image\ImageEditor;
use App\FieldArea;
use App\Record;
use App\RecordIssuerType;
use App\Template;

class RecordController extends Controller
{
    public static $record_issuer_types;

    public function __construct() {
        $this->middleware('auth');

        // an assoc. array of id => type
        self::$record_issuer_types = RecordIssuerType::idToType();
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
        $file_name = "{$record->issuer->name}_{$record->issue_date->toDateString()}.{$url_parts['extension']}";

        return response()->download($url, $file_name);
    }

    public function destroy(Record $record) {
        $this->authorize('belongs_to_user', $record);

        $record->delete();

        return back();
    }

    public function edit(Record $record)
    {
        $this->authorize('belongs_to_user', $record);
        /*
         $is_issuer_type_bill is
         used to determine whether to hide bill related form controls in views.
         e.g only bills have due date but not bank statements.
         */
        $is_issuer_type_bill = $record->is_issuer_type_bill();
        return view('records.edit', compact('record', 'is_issuer_type_bill'));
    }

    // TODO: should redirect to record issuer page, not back to the edit page!
    /*
    public function update(UpdateRecordForm $request, Record $record)
    {
        // TODO: move this authorization policy to UpdateRecordForm instead
        $this->authorize('belongs_to_user', $record);
        // add Gate:: here, allow(some policy) if auth()-id() === post(id) : allow else deny
        $this->upload_file($request, $record);
        if ($request)
        {
            $record->update($request->all());
            session()->flash('success', 'Records updated.');
        } //call update only if there's changes
        return back();
    }
    */

    // TODO: should delete old file if issue_date updated???
    // TODO: this is buggy -> it assumes that issue_date is present in the request
    /*
    private function upload_file($request, $record)
    {
        // upload only if user optionally uploaded a file
        if ($request ->file('record_file'))
        {
            $file          = $request->file('record_file');
            $extension     = $file->extension();
            $file_name     = "{$record->id}.{$extension}";
            $record_issuer = $record->issuer;
            $path_to_store = "record_issuers/{$record_issuer->id}/records";

            return $path_of_uploaded_file = $file->storeAs($path_to_store, $file_name, ['visibility'=>'private']);
        }
        return null;
    }
    */

    public function add_template(Record $record) {
        $this->authorize('belongs_to_user', $record);

        // Determine field_area_inputs based on type first
        $is_bill = $record->issuer->type === RecordIssuerType::BILLORG_TYPE_ID;
        $field_area_names = ['issue_date', 'period', 'amount'];
        $field_area_attrs = ['page', 'x', 'y', 'w', 'h'];
        if ($is_bill) {
            $field_area_names = array_merge($field_area_names, ['due_date']);
        }

        // Fill with null by default
        $field_area_inputs = [];
        foreach ($field_area_names as $field_area_name) {
            foreach ($field_area_attrs as $attr) {
                $field_area_inputs["{$field_area_name}_{$attr}"] = null;
            }
        }

        // Fill with existing field_area values if template exists
        if ($record->template !== null) {
            foreach ($field_area_names as $field_area_name) {
                $area_attr_name = $field_area_name . '_area';
                $field_area = $record->template->$area_attr_name;

                foreach ($field_area_attrs as $attr) {
                    $field_area_inputs["{$field_area_name}_{$attr}"] = $field_area->$attr;
                }
            }
        }

        $edit_value_mode = false;

        return view(
            'records.experimental_edit',
            compact('record', 'is_bill', 'field_area_inputs', 'edit_value_mode')
        );
    }

    // TODO: Warn user if duplicate record
    public function store_template(Record $record) {
        $this->authorize('belongs_to_user', $record);

        // Get the coords (and validate)
        // TODO: extract these long lists of validation to a specialized form handler and do typecasting
        $field_area_names = ['issue_date', 'period', 'amount'];
        $field_area_attrs = ['page', 'x', 'y', 'w', 'h'];
        $is_bill = $record->issuer->type === RecordIssuerType::BILLORG_TYPE_ID;
        if ($is_bill) {
            $field_area_names = array_merge($field_area_names, ['due_date']);
        }

        $rules = [];
        foreach ($field_area_names as $field_area_name) {
            foreach($field_area_attrs as $attr) {
                $rules["{$field_area_name}_{$attr}"] = 'required';
            }
        }

        // Expect from client: issue_date_page, issue_date_x, ...
        $this->validate(request(), $rules);



        $is_match = self::matches_template($record, $field_area_names); // true if all field area matches
        // $is_match is true only if template exists and matches the request data
        if (!$is_match) {
            $template = self::create_new_template($record, $field_area_names);
        } else {
            $template = $record->template;
        }



        // Interpret the texts using OCR, save the value
        $ocr_results = self::runOcr($record, $template, $field_area_names);
        $record->update($ocr_results);



        $field_area_inputs = request()->all();
        unset($field_area_inputs['_token']);
        $edit_value_mode = true;

        // Pass back to the same page, with coords and values filled
        return view(
            'records.experimental_edit',
            compact('record', 'is_bill', 'field_area_inputs', 'edit_value_mode')
        );
    }

    // TODO: need to validate date format received
    public function confirm_values(Record $record) {
        $this->authorize('belongs_to_user', $record);

        $is_bill = $record->issuer->type === RecordIssuerType::BILLORG_TYPE_ID;
        $field_area_names = ['issue_date', 'period', 'amount'];
        if ($is_bill) {
            $field_area_names = array_merge($field_area_names, ['due_date']);
        }

        foreach ($field_area_names as $field_area_name) {
            $rules[$field_area_name] = 'required';
        }

        $this->validate(request(), $rules);

        DB::transaction(function() use ($record, $field_area_names) {
            $record->update(array_merge(request($field_area_names), ['temporary' => false]));

            if ($record->issuer->active_template()) {
                $record->issuer->active_template()->update(['active' => false]);
            }
            $record->template->update(['active' => true]);
        });

        // TODO: Need a mechanism to show user the temp records when they logged in

        return redirect()->route('show_record_issuer', $record->issuer);
    }

    private static function matches_template(Record $record, $field_area_names) {
        $is_match = false; // true if all field area matches
        if ($record->template !== null) {
            $is_match = true;

            foreach ($field_area_names as $field_area_name) {
                $area_attr_name = $field_area_name . '_area';
                $field_area = $record->template->$area_attr_name;

                $record_page = $record->pages[$field_area->page];
                $page_geometry = ImageEditor::getImageGeometry(StorageHelper::getAbsolutePath($record_page->path));

                $page_match = $field_area->page === (int) request("{$field_area_name}_page");

                // allow +- 1 pixel deviation.
                $x_match = abs(($field_area->x - (double) request("{$field_area_name}_x")) * $page_geometry['width']) <= 1;
                $y_match = abs(($field_area->y - (double) request("{$field_area_name}_y")) * $page_geometry['height']) <= 1;
                $w_match = abs(($field_area->w - (double) request("{$field_area_name}_w")) * $page_geometry['width']) <= 1;
                $h_match = abs(($field_area->h - (double) request("{$field_area_name}_h")) * $page_geometry['height']) <= 1;

                $does_field_area_match = $page_match && $x_match && $y_match && $w_match && $h_match;

                if (!$does_field_area_match) {
                    $is_match = false;
                    break;
                }
            }
        }

        return $is_match;
    }

    private static function create_new_template(Record $record, $field_area_names) {
        $template_data = [];
        foreach ($field_area_names as $field_area_name) {
            $field_area_data = [];

            $field_area_data['page'] = request("{$field_area_name}_page");
            $field_area_data['x'] = request("{$field_area_name}_x");
            $field_area_data['w'] = request("{$field_area_name}_w");
            $field_area_data['y'] = request("{$field_area_name}_y");
            $field_area_data['h'] = request("{$field_area_name}_h");

            $template_data["{$field_area_name}_area_id"] = FieldArea::create($field_area_data)->id;
        }

        $created_template = $record->issuer->create_template(
            new Template(array_merge($template_data, ['active' => false]))
        );

        // Set record to point to $template
        $record->update([
            'template_id' => $created_template->id
        ]);

        return $created_template;
    }

    private static function runOcr(Record $record, Template $template, $field_area_names) {
        $record_images_dir_path = StorageHelper::createRecordImagesDir($record);

        $ocr_results = [];
        foreach ($field_area_names as $field_area_name) {
            $area_attr_name = $field_area_name . '_area';
            $field_area = $template->$area_attr_name;
            $crop_input_filename = StorageHelper::getAbsolutePath($record_images_dir_path . $field_area->page . ".jpg");
            $crop_output_filename = StorageHelper::getAbsolutePath($record_images_dir_path . $field_area_name . ".jpg");

            $record_page = $record->pages[$field_area->page];
            $page_geometry = ImageEditor::getImageGeometry(StorageHelper::getAbsolutePath($record_page->path));

            $actual_x = (int) ($field_area->x * $page_geometry['width']);
            $actual_y = (int) ($field_area->y * $page_geometry['height']);
            $actual_w = (int) ceil($field_area->w * $page_geometry['width']);
            $actual_h = (int) ceil($field_area->h * $page_geometry['height']);

            ImageEditor::cropJpeg(
                $crop_input_filename, $crop_output_filename,
                $actual_x, $actual_y, $actual_w, $actual_h
            );
            $ocr_results[$field_area_name] = ImageEditor::recognizeTextFromJpeg($crop_output_filename);
        }

        return $ocr_results;
    }
}
