<?php

namespace App\Policies;

use App\User;
use App\Attachment;
use App\Helpers\Authorize;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttachmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the Attachment.
     *
     * @param  \App\User  $user
     * @param  \App\Attachment  $attachment
     * @return mixed
     */
    public function delete(User $user, Attachment $attachment)
    {
        return $user->isAdmin() || $attachment->owner->id == $user->id;
    }

}
