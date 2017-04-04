<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecordPage extends Model
{
    public $fillable = ['path', 'record_id'];

    protected static function boot() {
        parent::boot();

        static::deleting(function($page) {
            DB::transaction(function () use ($page) {
                if(Storage::exists($page->path)) {
                    Storage::delete($page->path);
                }
            });
        });
    }

    public function record() {
        return $this->belongsTo(Record::class);
    }
}
