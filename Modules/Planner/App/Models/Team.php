<?php

namespace Modules\Planner\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Planner\Database\factories\TeamFactory;
use Modules\User\App\Models\User;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'team_user',
            'team_id',
            'user_id'
        )->withPivot('role')
        ->withTimestamps();
    }
    protected static function newFactory(): TeamFactory
    {
        //return TeamFactory::new();
    }
}
