<?php

namespace Modules\Support\Repositories;

use Modules\Support\App\Models\Ticket;
use Modules\Support\App\Models\TicketAttachemnt;
use Modules\Support\App\Models\TicketAttachments;
use Modules\Support\App\Models\TicketMessage;

class TicketRepository
{
    public function get_user_tickets($user_id)
    {
        return Ticket::where('user_id', $user_id)
            ->with(['messages.attachments'])
            ->orderByRaw("
        FIELD(
            status,
            'waiting customer res',
            'open',
            'waiting review',
            'dev progress',
            'solved'
        )
    ")
            ->get();

    }

    public function create_ticket($data)
    {
        return Ticket::create($data);
    }

    public function get_max_code()
    {
        return Ticket::max('code');

    }

    public function create_attachment($data)
    {
        return TicketAttachments::create($data);
    }

    public function create_message_ticket($data)
    {
        return TicketMessage::create($data);
    }

    public function get_ticket_by_id($id)
    {
        return Ticket::with('messages.user.role')->find($id);
    }
}
