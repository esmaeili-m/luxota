<?php

namespace Modules\Support\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
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

    public function update($id, array $data)
    {
        $ticketMessage = Ticket::find($id);
        if (!$ticketMessage) {
             return throw new ModelNotFoundException("TicketMessage not found");
        }
        $ticketMessage->update($data);
        return $ticketMessage;
    }


    public function get_ticket_by_id($id)
    {
        return Ticket::with('user','messages.user.role')->find($id);
    }

    public function ticket_count()
    {
        return Ticket::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

    }
    public function find(int $id, array $with = [])
    {
        return TicketMessage::findOrFail($id);
    }
    public function delete(TicketMessage $ticketMessage): bool
    {
        return $ticketMessage->delete();
    }
    public function get_tickets(array $filters=[])
    {
        return Ticket::search($filters)->with(['user'])->orderBy('last_reply_at','desc')->get();
    }
}
