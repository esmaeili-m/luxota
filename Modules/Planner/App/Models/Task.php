<?php

namespace Modules\Planner\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Planner\Database\factories\TaskFactory;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): TaskFactory
    {
        //return TaskFactory::new();
    }

    public function scopeSearch($query, $filters)
    {
        foreach ($filters ?? [] as $field => $value) {
            switch ($field) {

                case 'content':
                    $query->where(function($q) use ($value) {
                        $q->where('title', 'like', "%{$value}%")
                            ->orWhere('subtitle', 'like', "%{$value}%");
                    });
                    break;

                case 'parent_id':
                    if ($value === 'null' || $value === null) {
                        $query->whereNull('parent_id');
                    }elseif ($value === 'notNull') {
                        $query->whereNotNull('parent_id');
                    } else {
                        $query->where('parent_id', $value);
                    }
                    break;

                case 'status':
                    $query->where('status', (int)$value);
                    break;
            }
        }

        return $query;
    }

}
