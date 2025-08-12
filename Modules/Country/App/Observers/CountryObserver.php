<?php

namespace Modules\Country\App\Observers;

use \Modules\Country\App\Models\Country;

class CountryObserver
{
    /**
     * Handle the Country "created" event.
     */
    public function created(Country $country): void
    {
        //
    }

    /**
     * Handle the Country "updated" event.
     */
    public function updated(Country $country): void
    {
        if ($country->isDirty('status')) {
            $newStatus = $country->status;
            
            // Update all cities in this country
            $country->cities()->update(['status' => $newStatus]);
            
            // Update all users in cities of this country
            $country->cities()->each(function ($city) use ($newStatus) {
                $city->users()->update(['status' => $newStatus]);
            });
        }
    }

    /**
     * Handle the Country "deleted" event.
     */
    public function deleted(Country $country): void
    {
        if (!$country->isForceDeleting()) {
            // Soft delete all cities in this country
            $country->cities()->each(function ($city) {
                $city->delete();
            });
        }
    }

    /**
     * Handle the Country "restored" event.
     */
    public function restored(Country $country): void
    {
        // Restore all cities in this country
        $country->cities()->withTrashed()->each(function ($city) {
            $city->restore();
        });
    }

    /**
     * Handle the Country "force deleted" event.
     */
    public function forceDeleted(Country $country): void
    {
        //
    }
} 