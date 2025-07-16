<?php

namespace Modules\Rank\App\Observers;

use \Modules\Rank\App\Models\Rank;

class RankObserver
{
    /**
     * Handle the Rank "created" event.
     */
    public function created(Rank $rank): void
    {
        //
    }

    /**
     * Handle the Rank "updated" event.
     */
    public function updated(Rank $rank): void
    {
        if ($rank->isDirty('status')) {
            $newStatus = $rank->status;
            $rank->users()->update(['status' => $newStatus]);
        }
    }

    /**
     * Handle the Rank "deleted" event.
     */
    public function deleted(Rank $rank): void
    {
        if (!$rank->isForceDeleting()) {
            $rank->users()->each(function ($user) {
                $user->delete();
            });
        }
    }

    /**
     * Handle the Rank "restored" event.
     */
    public function restored(Rank $rank): void
    {
        $rank->users()->withTrashed()->each(function ($user) {
            $user->restore();
        });
    }

    /**
     * Handle the Rank "force deleted" event.
     */
    public function forceDeleted(Rank $rank): void
    {
        //
    }
} 