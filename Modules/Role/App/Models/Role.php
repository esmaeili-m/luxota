<?php

namespace Modules\Role\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Role\Database\factories\RoleFactory;

class Role extends Model
{
    use HasFactory,SoftDeletes;
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];


}
