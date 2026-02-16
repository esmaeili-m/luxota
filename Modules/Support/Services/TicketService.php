<?php

namespace Modules\Support\Services;

use App\Services\Uploader;
use Illuminate\Support\Str;
use Modules\Support\Repositories\TicketRepository;

class TicketService
{
    public TicketRepository $repo;

    public function __construct(TicketRepository $repo)
    {
        $this->repo=$repo;
    }
    public function get_user_tickets()
    {
        $message=auth()->user()->id;
        return $this->repo->get_user_tickets($message);
    }

    public function create_ticket(array $data)
    {
        $messageId = $data['user_id'] ?? auth()->user()->id;

        // اگر reply است
        if (isset($data['ticket_id']) && $data['ticket_id']) {
            $ticketId = $data['ticket_id'];
        } else {
            // ایجاد تیکت جدید
            $ticketData = [
                'subject' => $data['subject'],
                'user_id' => $messageId,
                'code' => $this->repo->get_max_code() + 1,
            ];

            $ticket = $this->repo->create_ticket($ticketData);
            $ticketId = $ticket->id;
        }

        // ایجاد پیام
        $messageData = [
            'ticket_id' => $ticketId,
            'user_id' => $messageId,
            'message' => $data['message'],
        ];

        $message = $this->repo->create_message_ticket($messageData);

        // ذخیره attachments (اگر وجود داشته باشد)
        if (!empty($data['attachments']) && is_array($data['attachments'])) {
            foreach ($data['attachments'] as $item) {
                $path = Uploader::uploadImage($item, 'tickets');
                $fileData = [
                    'disk' => 'public',
                    'ticket_message_id' => $message->id,
                    'file_path' => $path,
                    'original_name' => $item->getClientOriginalName(),
                    'mime_type' => $item->getMimeType(),
                    'size' => $item->getSize(),
                ];
                $this->repo->create_attachment($fileData);
            }
        }
        $data_time=[
          'last_reply_at' => now(),
        ];
        if (!empty($data['status'])){
            $data_time['status']=3;
        }
        $this->repo->update($ticketId,$data_time);

        return $ticket ?? $this->repo->get_ticket_by_id($ticketId);
    }

    public function get_ticket_by_id($id)
    {
        return $this->repo->get_ticket_by_id($id);

    }

    public function ticket_count()
    {
        $data=$this->repo->ticket_count();
        $data = collect($data)->mapWithKeys(function ($value, $key) {
            return [Str::snake($key) => $value];
        });

        return response()->json($data);


    }

    public function get_tickets(array $params)
    {
        $filters = [
            'status' => $params['status'] ?? null,
            'content' => $params['content'] ?? null,
            'code' => $params['code'] ?? null,
            'user_id' => $params['user_id'] ?? null,
        ];
        return $this->repo->get_tickets($filters);
    }

    public function update(int $id, array $data)
    {
        return $this->repo->update($id, $data);
    }
    public function delete(int $id): bool
    {

        $message = $this->repo->find($id);
        if (!$message) {
            return false;
        }
        return $message->delete();
    }
}
