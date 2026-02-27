<?php

namespace Modules\Planner\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Planner\Database\factories\CommentFactory;
use Modules\User\App\Models\User;

class Comment extends Model
{
    use HasFactory;
    protected $table='task_comments';

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->latest();
    }
    protected static function newFactory(): CommentFactory
    {
        //return CommentFactory::new();
    }
}
