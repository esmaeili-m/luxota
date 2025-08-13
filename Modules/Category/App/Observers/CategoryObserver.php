<?php

namespace Modules\Category\App\Observers;

use \Modules\Category\App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        if ($category->isDirty('status')) {
            $newStatus = $category->status;
            $category->children()->update(['status' => $newStatus]);
        }
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        if (!$category->isForceDeleting()) {
            $category->children()->each(function ($user) {
                $user->delete();
            });
        }
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        $category->children()->withTrashed()->each(function ($user) {
            $user->restore();
        });
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        $category->children()->withTrashed()->each(function ($user) {
            $user->forceDelete();
        });
    }
}
