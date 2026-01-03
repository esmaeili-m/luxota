<?php

namespace Modules\Support\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Support\Database\factories\TicketMessageFactory;
use Modules\User\App\Models\User;

class TicketMessage extends Model
{
    use HasFactory;
    protected $guarded=[];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    public function Attachments()
    {
        return $this->hasMany(TicketAttachments::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function newFactory(): TicketMessageFactory
    {
        //return TicketMessageFactory::new();
    }
}
