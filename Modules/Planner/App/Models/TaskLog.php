<?php

namespace Modules\Planner\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Planner\Database\factories\TaskLogFactory;
use Modules\User\App\Models\User;

class TaskLog extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ریلیشن با بورد مبدا و مقصد
    public function fromBoard()
    {
        return $this->belongsTo(Board::class, 'from_board_id');
    }

    public function toBoard()
    {
        return $this->belongsTo(Board::class, 'to_board_id');
    }

    // ریلیشن با ستون مبدا و مقصد
    public function fromColumn()
    {
        return $this->belongsTo(Column::class, 'from_column_id');
    }

    public function toColumn()
    {
        return $this->belongsTo(Column::class, 'to_column_id');
    }

}
