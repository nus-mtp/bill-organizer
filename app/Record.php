<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    /**
     * Cast attr => data_type only on get. This doesn't apply on set
     * @var array
     */
    protected $casts = [
        'period' => 'date',
        'issue_date' => 'date',
        'due_date' => 'date'
    ];

    public $fillable = ['issue_date', 'due_date', 'period', 'amount', 'path_to_file',
        'user_record_issuer_id'];

    public function issuer() {
        return $this->belongsTo(UserRecordIssuer::class, 'user_record_issuer_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function setPeriodAttribute($value) {
        $this->attributes['period'] = Carbon::parse($value);
    }
}
