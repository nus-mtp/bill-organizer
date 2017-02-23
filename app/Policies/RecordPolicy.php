<?php

namespace App\Policies;

use App\User;
use App\Record;
use App\UserRecordIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecordPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function record_issuer_belongs_to_user(User $user, UserRecordIssuer $user_record_issuer) {
        return $user->id === $user_record_issuer->id;
    }

    public function belongs_to_user(User $user, Record $record) {
        return $user->id === $record->user_id;
    }
}
