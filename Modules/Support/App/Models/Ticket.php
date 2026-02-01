<?php

namespace Modules\Support\App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Support\Database\factories\TicketFactory;
use Modules\User\App\Models\User;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): TicketFactory
    {
        //return TicketFactory::new();
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeSearch(Builder $query, array $filters = [])
    {
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['content'])) {
            $content = $filters['content'];
            $query->where(function ($q) use ($content) {
                $q->where('subject', 'LIKE', "%{$content}%")
                    ->orWhereHas('messages', function ($q2) use ($content) {
                        $q2->where('message', 'LIKE', "%{$content}%");
                    });
            });
        }

        if (!empty($filters['code'])) {
            $cleanCode = preg_replace('/\D/', '', $filters['code']);
            if (!empty($cleanCode)) {
                $query->where('code', 'LIKE', "%{$cleanCode}%");
            }
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query;
    }
}
