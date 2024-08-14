<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Forum\ForumThread;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the thread.
     */
    public function update(User $user, ForumThread $thread)
    {
        // Allow update if the user is the owner of the thread
        return $user->id === $thread->user_id;
    }

    // You can add more methods here for other actions, like delete, view, etc.
}
