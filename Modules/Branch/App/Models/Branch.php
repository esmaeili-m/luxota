<?php

namespace Modules\Branch\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Branch\Database\factories\BranchFactory;

class Branch extends Model
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
     * Get the users that belong to this branch.
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }
}
