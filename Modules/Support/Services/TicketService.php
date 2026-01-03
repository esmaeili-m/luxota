<?php

namespace Modules\Support\Services;

use App\Services\Uploader;
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
        $user=auth()->user()->id;
        return $this->repo->get_user_tickets($user);
    }

    public function create_ticket($data)
    {
        $ticket_data['subject']=$data['subject'];
        $ticket_data['user_id']=auth()->user()->id;
        $ticket_data['code']=$this->repo->get_max_code()+1;
        $ticket=$this->repo->create_ticket($ticket_data);
        $message['ticket_id']=$ticket->id;
        $message['user_id']=auth()->user()->id;
        $message['message']=$ticket->message;
        $message=$this->repo->create_message_ticket($message);
        foreach ($data['attachments'] as $key => $item){
            $path = Uploader::uploadImage($item, 'tickets');
            $file['disk']='public';
            $file['ticket_message_id']=$message->id;
            $file['file_path']=$path;
            $file['original_name']=$item->getClientOriginalName();
            $file['mime_type']= $item->getMimeType();
            $file['size']=$item->getSize();
            $this->repo->create_attachment($file);
        }
        return $ticket;

    }

    public function get_ticket_by_id($id)
    {
        return $this->repo->get_ticket_by_id($id);

    }
}
