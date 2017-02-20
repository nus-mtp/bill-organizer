<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRecordIssuer extends Model
{
    protected $primaryKey = 'name';
    public $incrementing = false;

    public $fillable = ['name'];

    public function user() {
        $this->belongsTo(User::class);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }
}
