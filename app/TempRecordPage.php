<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempRecordPage extends Model
{
    public $fillable = ['path', 'temp_record_id'];

    public function temp_record() {
        return $this->belongsTo(TempRecord::class);
    }
}
