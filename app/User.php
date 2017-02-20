<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function record_issuers() {
        return $this->hasMany(UserRecordIssuer::class);
    }

    public function create_record_issuer(UserRecordIssuer $record_issuer) {
        return $this->record_issuers()->save($record_issuer);
    }
}
