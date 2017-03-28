<?php

namespace App\Http\Controllers;

use App\Record;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Image\ImageEditor;

use App\FieldArea;
use App\RecordIssuerType;
use App\RecordPage;
use App\Template;
use App\TempRecord;

class TempRecordController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    // TODO: add a policy!
    // TODO: clean up. You made it work. Now make it right
    public function show_extract_coords_page(TempRecord $temp_record) {
        // Determine field_area_inputs based on type first
        $is_bill = $temp_record->record_issuer->type === RecordIssuerType::BILLORG_TYPE_ID;
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

        // Check for existing template (record specific or record_issuer specific)
        $chosen_template = null;
        if ($temp_record->template !== null) {
            $chosen_template = $temp_record->template;
        } else if ($temp_record->record_issuer->latest_template() !== null) {
            $chosen_template = $temp_record->record_issuer->latest_template();

        }

        // Fill with existing field_area values if template exists
        if ($chosen_template !== null) {
            foreach ($field_area_names as $field_area_name) {
                $area_attr_name = $field_area_name . '_area';
                $field_area = $chosen_template->$area_attr_name;

                $record_page = $temp_record->pages[$field_area->page];
                $page_geometry = ImageEditor::getImageGeometry(storage_path('app/' . $record_page->path));
                $field_area->x /= $page_geometry['width'];
                $field_area->w /= $page_geometry['width'];
                $field_area->y /= $page_geometry['height'];
                $field_area->h /= $page_geometry['height'];

                foreach ($field_area_attrs as $attr) {
                    $field_area_inputs["{$field_area_name}_{$attr}"] = $field_area->$attr;
                }
            }
        }

        $edit_value_mode = false;

        // TODO: If there's already a template, embed the coordinates and pre-select the boxes
        return view(
            'records.experimental_edit',
            compact('temp_record', 'is_bill', 'field_area_inputs', 'edit_value_mode')
        );
    }

    // TODO: Should I store the coords as normalized coords in DB?
    // TODO: Warn user if duplicate record
    public function extract_coords(TempRecord $temp_record) {
        // Get the coords (and validate)
        // TODO: extract these long lists of validation to a specialized form handler and do typecasting
        // TODO: creation of many models should be inside a DB transaction to maintain integrity
        $field_area_names = ['issue_date', 'period', 'amount'];
        $field_area_attrs = ['page', 'x', 'y', 'w', 'h'];
        $is_bill = $temp_record->record_issuer->type === RecordIssuerType::BILLORG_TYPE_ID;
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

        // Check if template exists -> compare the fields with existing one
        $chosen_template = null;
        if ($temp_record->template !== null) {
            $chosen_template = $temp_record->template;
        } else if ($temp_record->record_issuer->latest_template() !== null) {
            $chosen_template = $temp_record->record_issuer->latest_template();
        }

        $is_match = $chosen_template !== null; // true if all field area matches
        if ($chosen_template !== null) {
            $does_field_area_match = true;
            foreach ($field_area_names as $field_area_name) {
                $area_attr_name = $field_area_name . '_area';
                $field_area = $chosen_template->$area_attr_name;

                // TODO: Remove this cursed ugly, duplicated code
                // TODO: Move comparing coords to helper
                $record_page = $temp_record->pages[$field_area->page];
                $page_geometry = ImageEditor::getImageGeometry(storage_path('app/' . $record_page->path));

                $page_match = $field_area->page === (int) request("{$field_area_name}_page");
                // allow +- 1 pixel deviation.
                // TODO: beautify
                $x_match = abs($field_area->x - ((double) request("{$field_area_name}_x")) * $page_geometry['width']) < 2;
                $y_match = abs($field_area->y - ((double) request("{$field_area_name}_y")) * $page_geometry['height']) < 2;
                $w_match = abs($field_area->w - ((double) request("{$field_area_name}_w")) * $page_geometry['width']) < 2;
                $h_match = abs($field_area->h - ((double) request("{$field_area_name}_h")) * $page_geometry['height']) < 2;

                $does_field_area_match = $page_match && $x_match && $y_match && $w_match && $h_match;

                if (!$does_field_area_match) {
                    $is_match = false;
                    break;
                }
            }
        }

        // $is_match is true only if template exists and matches the request data
        if ($is_match) {
            // if match template, point to that template
            $final_template = $chosen_template;
        } else {
            // Otherwise (whether template doens't exist or doesn't match), create a new template with field areas
            $template_data = [];
            foreach ($field_area_names as $field_area_name) {
                $field_area_data = [];

                $field_area_data['page'] = request("{$field_area_name}_page");
                $record_page = $temp_record->pages[$field_area_data['page']];
                $page_geometry = ImageEditor::getImageGeometry(storage_path('app/' . $record_page->path));

                $field_area_data['x'] = (int) (request("{$field_area_name}_x") * $page_geometry['width']);
                $field_area_data['w'] = (int) ceil(request("{$field_area_name}_w") * $page_geometry['width']);
                $field_area_data['y'] = (int) (request("{$field_area_name}_y") * $page_geometry['height']);
                $field_area_data['h'] = (int) ceil(request("{$field_area_name}_h") * $page_geometry['height']);

                $template_data["{$field_area_name}_area_id"] = FieldArea::create($field_area_data)->id;
            }

            $final_template = $temp_record->record_issuer->create_template(
                new Template($template_data)
            );
        }

        // Set temp_record to point to $template
        $temp_record->update([
            'template_id' => $final_template->id
        ]);

        // Extract images by the coordinates and store it in temp dir
        // Interpret the texts using Tesseract, save the value
        // Delete the whole dir
        // TODO: copy and pasted from RecordIssuerController -- refactor this but make it work first!
        $user_id = auth()->id();
        $temp_images_dir_path = "tmp/users/{$user_id}/record_issuers/{$temp_record->record_issuer->id}/records/" .
            "{$temp_record->id}/img/";
        $cropped_dir_path = $temp_images_dir_path . "cropped/";
        if(!Storage::exists($cropped_dir_path)) {
            Storage::makeDirectory($cropped_dir_path, 0777, true, true);
        }

        // TODO: Don't do OCR if matches record's template?
        $ocr_results = [];
        foreach ($field_area_names as $field_area_name) {
            $area_attr_name = $field_area_name . '_area';
            $field_area = $final_template->$area_attr_name;
            $crop_input_filename = storage_path('app/' . $temp_images_dir_path . $field_area->page . ".jpg");
            $crop_output_filename = storage_path('app/' . $cropped_dir_path . $field_area_name . ".jpg");
            ImageEditor::cropJpeg(
                $crop_input_filename, $crop_output_filename,
                $field_area->x, $field_area->y, $field_area->w, $field_area->h
            );
            $ocr_results[$field_area_name] = ImageEditor::recognizeTextFromJpeg($crop_output_filename);
        }

        $temp_record->update($ocr_results);

        $field_area_inputs = request()->all();
        unset($field_area_inputs['_token']);
        $edit_value_mode = true;
        // Pass back to the same page, with coords and values filled
        return view(
            'records.experimental_edit',
            compact('temp_record', 'is_bill', 'field_area_inputs', 'edit_value_mode')
        );

        // User has to confirm or edit the value field
        // Press OK
        // Pass to store_record_experimental, move the file to permanent place and also the pages
    }

    public function confirm_values(TempRecord $temp_record) {
        $is_bill = $temp_record->record_issuer->type === RecordIssuerType::BILLORG_TYPE_ID;
        $field_area_names = ['issue_date', 'period', 'amount'];
        if ($is_bill) {
            $field_area_names = array_merge($field_area_names, ['due_date']);
        }

        foreach ($field_area_names as $field_area_name) {
            $rules[$field_area_name] = 'required';
        }

        $this->validate(request(), $rules);

        $temp_record->update(request($field_area_names));

        // link record to template
        DB::transaction(function ()  use ($temp_record) {
            // link record to temprecord's doc
            // do i need to append 'app/'?
            $user_id = auth()->id();
            $record_issuer = $temp_record->record_issuer;
            $dest_path = "users/{$user_id}/record_issuers/{$record_issuer->id}/records/";
            $src_path = storage_path('app/' . $temp_record->path_to_file);
            // TODO: is putFile or move better?
            $path = Storage::putFile($dest_path, new File($src_path));

            $record = auth()->user()->create_record(
                new Record([
                    'record_issuer_id' => $temp_record->record_issuer->id,
                    'template_id' => $temp_record->template->id,
                    'issue_date' => $temp_record->issue_date,
                    'due_date' => $temp_record->due_date,
                    'period' => $temp_record->period,
                    'amount' => $temp_record->amount,
                    'path_to_file' => $path
                ])
            );

            // link record to temprecord's pages
            foreach ($temp_record->pages as $page) {
                $dest_path = "users/{$user_id}/record_issuers/{$record_issuer->id}/records/" .
                    "{$record->id}/img/";
                $src_path = storage_path('app/' .$page->path);
                $basename = pathinfo($page->path)['basename'];
                $path = Storage::putFileAs($dest_path, new File($src_path), $basename);
                $record->pages()->save(
                    new RecordPage(['path' => $path])
                );
            }

            $temp_record->delete();
        });
        // TODO: delete the respective tmp dir
        // TODO: delete the temp records
        // TODO: Need a mechanism to show user the temp records when they logged in
        // TODO: clean up resources (for instance, delete unused field areas, etc)

        return redirect()->route('show_record_issuer', $temp_record->record_issuer);
    }
}
