<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecordType extends Model
{
    public function type() {
        return $this->getAttribute('type');
    }

    public function amount_field_name() {
        return $this->getAttribute('amount_field_name');
    }
}
