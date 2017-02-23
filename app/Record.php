<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    public $fillable = ['issue_date', 'due_date', 'period', 'amount', 'path_to_file',
        'user_record_issuer_id'];

    public function issuer() {
        return $this->belongsTo(UserRecordIssuer::class, 'user_record_issuer_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
