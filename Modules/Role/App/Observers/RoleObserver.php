<?php

namespace Modules\Role\App\Observers;

use \Modules\Role\App\Models\Role;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        //
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        if ($role->isDirty('status')) {
            $newStatus = $role->status;
            $role->users()->update(['status' => $newStatus]);
        }
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        if (!$role->isForceDeleting()) {
            $role->users()->each(function ($user) {
                $user->delete();
            });
        }
    }

    /**
     * Handle the Role "restored" event.
     */
    public function restored(Role $role): void
    {
        $role->users()->withTrashed()->each(function ($user) {
            $user->restore();
        });
    }


    public function forceDeleted(Role $role): void
    {

    }
}
