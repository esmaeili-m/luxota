<?php

namespace Modules\ActivityLog\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ActivityLog\Database\factories\ActivityLogFactory;
use Modules\User\App\Models\User;

class ActivityLog extends Model
{
    use HasFactory;
    protected $table ='activity_log';
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): ActivityLogFactory
    {
        //return ActivityLogFactory::new();
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','causer_id');
    }
}
