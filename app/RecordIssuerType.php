<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecordIssuerType extends Model
{
  // record issuer type name stored in database
    const BILLORG_TYPE_NAME =  'billing organization';
    const BANK_STATEMENT_TYPE_NAME = 'bank';
    const BILLORG_TYPE_ID = 1;
    const BANK_TYPE_ID = 2;

    public function scopeType($query, $type){
        return $query->where('type', $type);
    }

    public static function random_type() {
        $record_issuer_types = self::pluck('id')->toArray();
        $rand_index = array_rand($record_issuer_types);
        return $record_issuer_types[$rand_index];
    }

   public function record_issuer()
  {
    return $this->hasMany(RecordIssuer::class,'type');
  }

}
