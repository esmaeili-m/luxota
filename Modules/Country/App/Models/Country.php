<?php

namespace Modules\Country\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Country\Database\Factories\CountryFactory;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    /**
     * Get the cities for the country.
     */
    public function cities()
    {
        return $this->hasMany(\Modules\City\App\Models\City::class);
    }

    /**
     * Get the zone for the country.
     */
    public function zone()
    {
        return $this->belongsTo(\Modules\Zone\App\Models\Zone::class);
    }

    protected static function newFactory(): CountryFactory
    {
        return CountryFactory::new();
    }
} 