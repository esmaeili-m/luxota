<?php

namespace Modules\City\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\City\Database\factories\CityFactory;

class City extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'country_id',
        'en',
        'abb',
        'priority',
        'status',
        'fa',
        'ar',
        'ku',
        'tr'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'priority' => 'integer',
    ];

    /**
     * Get the country that owns the city.
     */
    public function country()
    {
        return $this->belongsTo(\Modules\Country\App\Models\Country::class);
    }

    /**
     * Get the users that belong to this city.
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }
}
