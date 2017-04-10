<?php

namespace App\Policies;

use App\User;
use App\RecordPage;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecordPagePolicy
{
    use HandlesAuthorization;

    public function belongs_to_user(User $user, RecordPage $recordPage) {
        return $user->id === $recordPage->record->user_id;
    }
}
