<?php

namespace Modules\Category\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Category\Database\factories\CategoryFactory;

class Category extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];

}
