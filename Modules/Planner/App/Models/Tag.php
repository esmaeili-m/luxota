<?php

namespace Modules\Planner\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Planner\Database\factories\TagFactory;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function children()
    {
        return $this->hasMany(Tag::class, 'parent_id')
            ->with('children'); // recursive
    }

    public function parent()
    {
        return $this->belongsTo(Tag::class, 'parent_id');
    }
}
