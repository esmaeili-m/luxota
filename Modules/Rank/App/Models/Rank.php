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
    protected $guarded= [];

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
    protected static function booted()
    {
        static::updated(function ($rank) {
            if ($rank->isDirty('status')) {
                foreach ($rank->users as $user) {
                    $user->status = $rank->status;
                    $user->save();
                }
            }
        });

        static::deleted(function ($rank) {
            foreach ($rank->users()->get() as $user) {
                $user->delete();
            }
        });

        static::restored(function ($rank) {
            foreach ($rank->users()->withTrashed()->get() as $user) {
                $user->restore();
            }
        });
    }
    public function scopeSearch($query, $fillters)
    {

        foreach ($fillters ?? [] as $filed){
            if (!$filed) {
                break;
            }
            switch ($filed) {
                case 'title':
                    $query->where('title', $filed);
                    break;

                case 'status':
                    $query->where('status', $filed);
                    break;


            }
        }

        return $query;
    }


}
