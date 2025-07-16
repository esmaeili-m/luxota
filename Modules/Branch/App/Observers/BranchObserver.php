<?php

namespace Modules\Branch\App\Observers;

use \Modules\Branch\App\Models\Branch;

class BranchObserver
{
    /**
     * Handle the Branch "created" event.
     */
    public function created(Branch $branch): void
    {
        //
    }

    /**
     * Handle the Branch "updated" event.
     */
    public function updated(Branch $branch): void
    {
        if ($branch->isDirty('status')) {
            $newStatus = $branch->status;
            $branch->users()->update(['status' => $newStatus]);
        }
    }

    /**
     * Handle the Branch "deleted" event.
     */
    public function deleted(Branch $branch): void
    {
        if (!$branch->isForceDeleting()) {
            $branch->users()->each(function ($user) {
                $user->delete();
            });
        }
    }

    /**
     * Handle the Branch "restored" event.
     */
    public function restored(Branch $branch): void
    {
        $branch->users()->withTrashed()->each(function ($user) {
            $user->restore();
        });
    }

    /**
     * Handle the Branch "force deleted" event.
     */
    public function forceDeleted(Branch $branch): void
    {
        //
    }
} 