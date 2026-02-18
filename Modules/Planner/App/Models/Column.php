<?php

namespace Modules\Planner\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Planner\Database\factories\ColumnFactory;

class Column extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    protected static function newFactory(): ColumnFactory
    {
        //return ColumnFactory::new();
    }
}
