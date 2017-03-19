<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecordPage extends Model
{
    public $fillable = ['path', 'record_id'];

    public function record() {
        return $this->belongsTo(Record::class);
    }
}
