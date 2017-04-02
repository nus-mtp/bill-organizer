<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TempRecordPage;

class TempRecordPageController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function show(TempRecordPage $temp_record_page) {
        // TODO: Add a policy!!!
        // $this->authorize('belongs_to_user', $temp_record_page);

        $url = storage_path('app/' . $temp_record_page->path);

        return response()->file($url);
    }
}
