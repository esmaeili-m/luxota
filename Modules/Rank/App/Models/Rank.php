<?php

namespace Modules\Rank\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Rank\Database\factories\RankFactory;

class Rank extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'status'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [

    ];

    /**
     * Get the users that belong to this rank.
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }
}
