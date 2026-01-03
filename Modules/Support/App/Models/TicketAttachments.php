<?php

namespace Modules\Support\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Support\Database\factories\TicketAttachemntFactory;

class TicketAttachments extends Model
{
    use HasFactory;
    protected $guarded=[];

    /**
     * The attributes that are mass assignable.
     */

    protected static function newFactory(): TicketAttachemntFactory
    {
        //return TicketAttachemntFactory::new();
    }
}
