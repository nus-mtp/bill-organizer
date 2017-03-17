<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempRecordPage extends Model
{
    public function temp_record() {
        return $this->belongsTo(TempRecord::class);
    }
}
