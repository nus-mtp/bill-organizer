<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = ['issue_date_area_id', 'due_date_area_id', 'period_area_id', 'amount_area_id'];

    public function record_issuer()
    {
        return $this->belongsTo(RecordIssuer::class);
    }

    public function issue_date_area()
    {
        return $this->hasOne(FieldArea::class, 'id', 'issue_date_area_id');
    }

    public function due_date_area()
    {
        return $this->hasOne(FieldArea::class, 'id', 'due_date_area_id');
    }

    public function period_area()
    {
        return $this->hasOne(FieldArea::class, 'id', 'period_area_id');
    }

    public function amount_area()
    {
        return $this->hasOne(FieldArea::class, 'id', 'amount_area_id');
    }
}
