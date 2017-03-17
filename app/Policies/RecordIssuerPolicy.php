<?php

namespace App\Policies;

use App\User;
use App\RecordIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecordIssuerPolicy
{
    use HandlesAuthorization;

    public function belongs_to_user(User $user, RecordIssuer $record_issuer) {
        return $user->id === $record_issuer->user_id;
    }

}
