<?php

namespace Modules\Planner\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Planner\Database\factories\TeamFactory;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): TeamFactory
    {
        //return TeamFactory::new();
    }
}
