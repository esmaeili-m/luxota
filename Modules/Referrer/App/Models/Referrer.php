<?php

namespace Modules\Referrer\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Referrer\Database\factories\ReferrerFactory;

class Referrer extends Model
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
     * Get the users that belong to this referrer type.
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }
}
