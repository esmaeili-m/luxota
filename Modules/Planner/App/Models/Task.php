<?php

namespace Modules\Planner\App\Models;

use App\Enums\BusinessStatus;
use App\Enums\HasInvoice;
use App\Enums\ImplementationType;
use App\Enums\TaskCategory;
use App\Enums\TaskPriority;
use App\Enums\TaskType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Planner\Database\factories\TaskFactory;
use Modules\Support\App\Models\Ticket;
use Modules\User\App\Models\User;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];



    protected static function newFactory(): TaskFactory
    {
        //return TaskFactory::new();
    }
    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /*
    |--------------------------------------------------------------------------
    | Self Relation (Parent / Children)
    |--------------------------------------------------------------------------
    */

    public function parentTask()
    {
        return $this->belongsTo(self::class, 'parent_task_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_task_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Attachments
    |--------------------------------------------------------------------------
    */

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function scopeSearch($query, $filters)
    {
        foreach ($filters ?? [] as $field => $value) {
            switch ($field) {

                case 'content':
                    $query->where(function($q) use ($value) {
                        $q->where('title', 'like', "%{$value}%")
                            ->orWhere('subtitle', 'like', "%{$value}%");
                    });
                    break;

                case 'parent_id':
                    if ($value === 'null' || $value === null) {
                        $query->whereNull('parent_id');
                    }elseif ($value === 'notNull') {
                        $query->whereNotNull('parent_id');
                    } else {
                        $query->where('parent_id', $value);
                    }
                    break;

                case 'status':
                    $query->where('status', $value);
                    break;
            }
        }

        return $query;
    }

}
