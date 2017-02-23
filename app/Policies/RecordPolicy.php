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

    public function store(User $user, UserRecordIssuer $user_record_issuer) {
        return $user->id === $user_record_issuer->id;
    }

    public function show_file(User $user, Record $record) {
        return $user->id === $record->user_id;
    }
}
