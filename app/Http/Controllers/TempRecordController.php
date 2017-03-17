<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TempRecord;

class TempRecordController extends Controller
{
    public function create(TempRecord $temp_record) {
        // Then get pages from DB and coords from template if exists
        $record_issuer = $temp_record->record_issuer;
        $template = $record_issuer->latest_template();

        $first_page = $temp_record->pages->first();

        // TODO: If there's already a template, embed the coordinates and pre-select the boxes
        return view('records.create', compact('temp_record', 'first_page'));
    }
}
