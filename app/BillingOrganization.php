<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillingOrganization extends Model
{
    protected $primaryKey = 'name';
    public $incrementing = false;

    public $fillable = ['name'];

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
