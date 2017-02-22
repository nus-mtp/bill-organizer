<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRecordIssuer extends Model
{
    public $fillable = ['name'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
