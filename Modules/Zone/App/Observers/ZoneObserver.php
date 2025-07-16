<?php

namespace Modules\Zone\App\Observers;

use \Modules\Zone\App\Models\Zone;

class ZoneObserver
{
    /**
     * Handle the Zone "created" event.
     */
    public function created(Zone $zone): void
    {
        //
    }

    /**
     * Handle the Zone "updated" event.
     */
    public function updated(Zone $zone): void
    {
        if ($zone->isDirty('status')) {
            $newStatus = $zone->status;
            $zone->users()->update(['status' => $newStatus]);
        }
    }

    /**
     * Handle the Zone "deleted" event.
     */
    public function deleted(Zone $zone): void
    {
        if (!$zone->isForceDeleting()) {
            $zone->users()->each(function ($user) {
                $user->delete();
            });
        }
    }

    /**
     * Handle the Zone "restored" event.
     */
    public function restored(Zone $zone): void
    {
        $zone->users()->withTrashed()->each(function ($user) {
            $user->restore();
        });
    }

    /**
     * Handle the Zone "force deleted" event.
     */
    public function forceDeleted(Zone $zone): void
    {
        //
    }
}
