<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RecordIssuer extends Model
{
    public $fillable = ['name', 'type'];

    protected static function boot() {
        parent::boot();

        static::deleting(function($record_issuer) {
            DB::transaction(function () use ($record_issuer) {
                $record_issuer->records()->delete();
                $record_issuer->templates()->delete();
            });
        });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function records()
    {
        return $this->hasMany(Record::class);
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

    public function create_template(Template $template)
    {
        return $this->templates()->save($template);
    }
}
