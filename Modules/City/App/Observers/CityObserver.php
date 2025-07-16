<?php

namespace Modules\City\App\Observers;

use \Modules\City\App\Models\City;

class CityObserver
{
    /**
     * Handle the City "created" event.
     */
    public function created(City $city): void
    {
        //
    }

    /**
     * Handle the City "updated" event.
     */
    public function updated(City $city): void
    {
        if ($city->isDirty('status')) {
            $newStatus = $city->status;
            $city->users()->update(['status' => $newStatus]);
        }
    }

    /**
     * Handle the City "deleted" event.
     */
    public function deleted(City $city): void
    {
        if (!$city->isForceDeleting()) {
            $city->users()->each(function ($user) {
                $user->delete();
            });
        }
    }

    /**
     * Handle the City "restored" event.
     */
    public function restored(City $city): void
    {
        $city->users()->withTrashed()->each(function ($user) {
            $user->restore();
        });
    }

    /**
     * Handle the City "force deleted" event.
     */
    public function forceDeleted(City $city): void
    {
        //
    }
} 