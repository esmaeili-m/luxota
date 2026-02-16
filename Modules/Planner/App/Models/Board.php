<?php

namespace Modules\Planner\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Planner\Database\factories\BoardFactory;

class Board extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function columns()
    {
        return $this->hasMany(Column::class);
    }
    public function scopeSearch($query, $filters)
    {
        foreach ($filters ?? [] as $field => $value) {
            switch ($field) {

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
    protected static function newFactory(): BoardFactory
    {
        //return BoardFactory::new();
    }
}
