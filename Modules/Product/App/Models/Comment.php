<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Database\factories\CommentFactory;
use Modules\User\App\Models\User;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }


    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    protected static function newFactory(): CommentFactory
    {
        //return CommentFactory::new();
    }
}
