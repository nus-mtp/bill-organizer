<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldArea extends Model
{
    public $fillable = ['page', 'x', 'y', 'w', 'h'];
}
