<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecordIssuer extends Model
{
    public $fillable = ['name', 'type'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }

    public function temp_records()
    {
        return $this->hasMany(TempRecord::class);
    }

    public function issuer_type()
    {
      return $this->belongsTo(RecordIssuerType::class, 'type');
    }

    /**
     * A RecordIssuer has many templates considering that it can update the template of the records it issues
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    /**
     * Get the latest defined template
     */
    public function latest_template()
    {
        return $this->templates()->orderBy('created_at', 'desc')->first();
    }
}
