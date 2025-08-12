<?php

namespace Modules\User\App\Observers;

use Modules\User\App\Models\User;

class UserObserver
{
    public function deleting(User $user)
    {
        if ($user->isForceDeleting()) {
            $user->children()->forceDelete();
        } else {
            $user->children()->delete();
        }
    }

    public function restoring(User $user)
    {
        $user->children()->withTrashed()->restore();
    }

    public function updated(User $user)
    {
        if ($user->isDirty('status')) {
            $newStatus = $user->status;
            $user->children()->update(['status' => $newStatus]);
        }
    }
}
