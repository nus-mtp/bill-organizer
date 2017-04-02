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

    /**
     * Eloquent relationships
     */
    public function record_issuers() {
        return $this->hasMany(RecordIssuer::class);
    }

    public function records() {
        return $this->hasMany(Record::class);
    }

    public function temp_records() {
        return $this->hasMany(TempRecord::class);
    }

    /**
     * CRUD on other models
     */

    public function create_record_issuer(RecordIssuer $record_issuer) {
        return $this->record_issuers()->save($record_issuer);
    }

    public function create_record(Record $record) {
        return $this->records()->save($record);
    }

    public function create_temp_record(TempRecord $record) {
        return $this->temp_records()->save($record);
    }
}
