<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\User\Database\factories\UserFactory;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'description',
        'avatar',
        'websites',
        'address',
        'luxota_website',
        'status',
        'country_code',
        'whatsapp_country_code',
        'whatsapp_number',
        'role_id',
        'zone_id',
        'city_id',
        'rank_id',
        'referrer_id',
        'branch_id',
        'parent_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(\Modules\Role\App\Models\Role::class);
    }

    /**
     * Get the zone that owns the user.
     */
    public function zone()
    {
        return $this->belongsTo(\Modules\Zone\App\Models\Zone::class);
    }

    /**
     * Get the city that owns the user.
     */
    public function city()
    {
        return $this->belongsTo(\Modules\City\App\Models\City::class);
    }

    /**
     * Get the rank that owns the user.
     */
    public function rank()
    {
        return $this->belongsTo(\Modules\Rank\App\Models\Rank::class);
    }

    /**
     * Get the referrer that owns the user.
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the branch that owns the user.
     */
    public function branch()
    {
        return $this->belongsTo(\Modules\Branch\App\Models\Branch::class);
    }

    /**
     * Get the parent that owns the user.
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the children for the user.
     */
    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    /**
     * Get the referred users for the user.
     */
    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referrer_id');
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
