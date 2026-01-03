<?php

namespace Modules\Support\App\resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
          'id' => $this->id,
          'subject' => $this->subject,
          'code' => '#CR'.$this->code,
          'messages'=> $this->whenLoaded('messages',function (){
              return $this->messages->map(function ($message){
                  return [
                        'message'=>$message->message,
                      'attachments' => $message->attachments->map(function ($file) {
                          return [
                              'file_path' => $file->file_path, 
                              'original_name' => $file->original_name,
                              'mime_type' => $file->mime_type,
                          ];
                      }),                        'user'=>$message->user->name,
                        'avatar'=>$message->user->avatar,
                        'role'=>$message->user->role->name,
                        'created_at'=>Carbon::parse($message->created_at)->format('Y-m-d H:i:s'),
                  ];
              });
          }),
          'last_reply_at' =>'LR: '. is_null($this->last_reply_at)
                ? null
                :  Carbon::parse($this->last_reply_at)->diffForHumans(),
          'date' => Carbon::parse($this->created_at)->diffForHumans(),
          'status' => $this->status,
          'created_at'=>$this->created_at,
           'opened_at'=>Carbon::parse($this->created_at)->diffForHumans()
        ];
    }
}
