<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempRecord extends Model
{
    public $fillable = ['path_to_file', 'record_issuer_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function record_issuer()
    {
        return $this->belongsTo(RecordIssuer::class);
    }

    public function issuer_type()
    {
        return $this->record_issuer->issuer_type();
    }

    public function pages()
    {
        return $this->hasMany(TempRecordPage::class);
    }
}
