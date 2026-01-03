<?php

namespace Modules\Support\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Support\Database\factories\TicketFactory;

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
}
