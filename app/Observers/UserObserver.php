<?php

namespace App\Observers;

use App\Models\User;
use Waad\ProfanityFilter\ProfanityFilter;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     */
    public function creating(User $user): void
    {
        // Only check if moderation_status is not already set
        if ($user->moderation_status === null) {
            $profanityFilter = new ProfanityFilter;

            if ($profanityFilter->hasProfanity($user->github_username)) {
                $user->moderation_status = 'pending';
            } else {
                $user->moderation_status = 'approved';
            }
        }
    }

    /**
     * Handle the User "updating" event.
     */
    public function updating(User $user): void
    {
        // If github_username is being changed, re-check for profanity
        if ($user->isDirty('github_username') && $user->isApproved()) {
            $profanityFilter = new ProfanityFilter;

            if ($profanityFilter->hasProfanity($user->github_username)) {
                $user->moderation_status = 'pending';
                $user->moderated_at = null;
            }
        }
    }
}
