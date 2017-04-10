<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Template extends Model
{
    protected $fillable = ['active', 'issue_date_area_id', 'due_date_area_id', 'period_area_id', 'amount_area_id'];

    protected static function boot() {
        parent::boot();

        static::deleted(function($template) {
            DB::transaction(function () use ($template) {
                $fieldAreaIds = [];
                $fieldAreaIds[] = $template->issue_date_area_id;
                $fieldAreaIds[] = $template->period_area_id;
                $fieldAreaIds[] = $template->due_date_area_id;
                $fieldAreaIds[] = $template->amount_area_id;

                FieldArea::destroy($fieldAreaIds);
            });
        });
    }

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
