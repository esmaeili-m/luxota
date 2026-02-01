<?php
namespace Modules\Notification\Messages;

use Modules\Notification\Contracts\NotificationMessageInterface;
use Modules\Support\App\Models\Ticket;
use Modules\User\App\Models\User;

class TicketClosedMessage implements NotificationMessageInterface
{
    public function __construct(
        protected Ticket $thread,
        protected User $agency,
        protected User $closedBy
    ) {}

    public function subject(): ?string
    {
        return 'Ticket Closed';
    }

    public function content(): string
    {
        return view('notification::messages.ticket_closed', [
            'agency' => $this->agency,
            'thread' => $this->thread,
            'closedBy' => $this->closedBy,
        ])->render();
    }
}
