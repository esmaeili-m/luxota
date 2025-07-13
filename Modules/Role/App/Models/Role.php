<?php

namespace Modules\Role\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Role\Database\factories\RoleFactory;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [

    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
    ];

    /**
     * Get the users that have this role.
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }
    // در مدل Role
    protected static function booted()
    {
        static::deleting(function ($role) {
            if (! $role->isForceDeleting()) {
                $role->users()->each(function ($user) {
                    $user->delete(); // soft delete
                });
            }
        });

        static::restoring(function ($role) {
            $role->users()->withTrashed()->each(function ($user) {
                $user->restore();
            });
        });
    }

}
