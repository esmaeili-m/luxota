<?php

namespace Modules\Country\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Country\Database\Factories\CountryFactory;
use Modules\Currency\App\Models\Currency;

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
     * The attributes that should be cast.
     */
    protected $casts = [
    ];

    /**
     * Get the zone for the country.
     */
    public function zone()
    {
        return $this->belongsTo(\Modules\Zone\App\Models\Zone::class);
    }

    public function currency()
    {
        return $this->belongsTo(\Modules\Currency\App\Models\Currency::class);

    }
    public function scopeSearch($query, $fillters)
    {

        foreach ($fillters ?? [] as $filed => $value){

            if (!$filed) {
                break;
            }

            switch ($value) {
                case 'en':
                    $query->where('en', $value);
                    break;
                case 'abb':
                    $query->where('abb', $value);
                    break;
                case 'phone_code':
                    $query->where('phone_code', $value);
                    break;
                case 'zone_id':
                    $query->where('zone_id', $value);
                    break;
                case 'currency_id':
                    $query->where('currency_id', $value);
                    break;
                case 'status':
                    $query->where('status', $value);
                    break;
            }
        }

        return $query;
    }


}
