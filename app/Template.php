<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    public function record_issuer()
    {
        return $this->belongsTo(RecordIssuer::class);
    }

    public function issue_date_area()
    {
        return $this->hasOne(FieldAreas::class, 'id', 'issue_date_area_id');
    }

    public function due_date_area()
    {
        return $this->hasOne(FieldAreas::class, 'id', 'due_date_area_id');
    }

    public function period_area()
    {
        return $this->hasOne(FieldAreas::class, 'id', 'period_area_id');
    }

    public function amount_area()
    {
        return $this->hasOne(FieldAreas::class, 'id', 'amount_area_id');
    }
}
