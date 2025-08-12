<?php

namespace Modules\Role\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Role\Database\factories\RoleFactory;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [

    ];


    protected $casts = [
    ];


    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }

}
