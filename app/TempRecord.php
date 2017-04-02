<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// TODO: Do we actually need a TempRecord?? Or is Record sufficient?
class TempRecord extends Model
{
    public $fillable = ['path_to_file', 'record_issuer_id', 'template_id', 'issue_date', 'issue_date', 'due_date',
        'period', 'amount'];

    public $casts = [
        'period' => 'date',
        'issue_date' => 'date',
        'due_date' => 'date'
    ];

    protected static function boot() {
        parent::boot();

        static::deleting(function($temp_record) {
            DB::transaction(function () use ($temp_record) {
                $temp_record->pages()->delete();
            });
        });
    }

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

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function setPeriodAttribute($value) {
        $this->attributes['period'] = Carbon::parse($value);
    }

    public function setIssueDateAttribute($value) {
        $this->attributes['issue_date'] = Carbon::parse($value);
    }

    public function setDueDateAttribute($value) {
        $this->attributes['due_date'] = Carbon::parse($value);
    }

    public function setAmountAttribute($value) {
        // trim $ if any
        $value = str_replace('$', '', $value);
        $this->attributes['amount'] = $value;
    }
}
