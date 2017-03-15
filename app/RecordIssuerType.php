<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecordIssuerType extends Model
{
  // record issuer type name stored in database
  const BILL_TYPE_NAME =  'billing organization';
  const BANK_STATEMENT_TYPE_NAME = 'bank';

  public function record_issuer()
  {
    return $this->hasMany(RecordIssuer::class,'type');
  }
}
