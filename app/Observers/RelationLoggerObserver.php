<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsRelations;

class RelationLoggerObserver
{
    public function updating(Model $model): void
    {
//        if (in_array(LogsRelations::class, class_uses_recursive($model))) {
//            $model->logRelatedChanges($model);
//        }
    }

    // 👉 اگر خواستی رو create/delete هم کار کنه می‌تونی اینا رو اضافه کنی
    /*
    public function created(Model $model): void
    {
        // ...
    }

    public function deleted(Model $model): void
    {
        // ...
    }
    */
}
