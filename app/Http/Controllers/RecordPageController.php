<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\RecordPage;

class RecordPageController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function show(RecordPage $record_page) {
        $this->authorize('belongs_to_user', $record_page);

        $url = storage_path('app/' . $record_page->path);

        return response()->file($url);
    }
}
