<?php

namespace App\Policies;

use App\User;
use App\Record;
use App\RecordIssuer;
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

    public function belongs_to_user(User $user, Record $record) {
        return $user->id === $record->user_id;
    }
}
