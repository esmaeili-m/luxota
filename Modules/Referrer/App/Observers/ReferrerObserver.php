<?php

namespace Modules\Referrer\App\Observers;

use \Modules\Referrer\App\Models\Referrer;

class ReferrerObserver
{
    /**
     * Handle the Referrer "created" event.
     */
    public function created(Referrer $referrer): void
    {
        //
    }

    /**
     * Handle the Referrer "updated" event.
     */
    public function updated(Referrer $referrer): void
    {
        if ($referrer->isDirty('status')) {
            $newStatus = $referrer->status;
            $referrer->users()->update(['status' => $newStatus]);
        }
    }

    /**
     * Handle the Referrer "deleted" event.
     */
    public function deleted(Referrer $referrer): void
    {
        if (!$referrer->isForceDeleting()) {
            $referrer->users()->each(function ($user) {
                $user->delete();
            });
        }
    }

    /**
     * Handle the Referrer "restored" event.
     */
    public function restored(Referrer $referrer): void
    {
        $referrer->users()->withTrashed()->each(function ($user) {
            $user->restore();
        });
    }

    /**
     * Handle the Referrer "force deleted" event.
     */
    public function forceDeleted(Referrer $referrer): void
    {
        $referrer->users()->withTrashed()->each(function ($user) {
            $user->forceDelete();
        });
    }
}
