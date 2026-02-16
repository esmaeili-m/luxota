<?php

namespace Modules\Support\App\resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    protected $statuses =[
      'UNKNOW',
      'OPEN',
      'WATTING REVIEW',
      'WATTING CUSTOMER RES',
      'DEV PROGRESS',
       'SOLVED'
    ];
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'code' => '#CR' . $this->code,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'whatsapp' => $this->user->country_code . $this->user->whatsapp_number,
                    'name' => $this->user->name,
                    'id' => $this->user->id,
                    'avatar' => asset('storage/' . $this->user->avatar),
                ];
            }),
            'messages' => $this->whenLoaded('messages', function () {
                return $this->messages->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'attachments' => $message->attachments->map(function ($file) {
                            return [
                                'url' => asset('storage/' . rawurlencode($file->file_path)),
                                'original_name' => $file->original_name,
                                'mime_type' => $file->mime_type,
                            ];
                        }),
                        'user' => $message->user->name,
                        'avatar' => asset('storage/' . $message->user->avatar),
                        'whatsapp' => $message->user->country_code . $message->user->whatsapp_number,
                        'role' => $message->user->role->name,
                        'created_at' => Carbon::parse($message->created_at)->format('Y-m-d H:i:s'),
                        'date' => Carbon::parse($message->created_at)->diffForHumans(),

                    ];
                });
            }),
            'last_reply_at' => is_null($this->last_reply_at)
                ? null
                : [
                    'text' => Carbon::parse($this->last_reply_at)->diffForHumans(),
                    'color' => Carbon::parse($this->last_reply_at)->diffInMinutes() > 60
                        ? 'warning'
                        : 'danger',
                ],

            'time' => is_null($this->last_reply_at) ? Carbon::parse($this->created_at)->diffForHumans()
                : Carbon::parse($this->last_reply_at)->diffForHumans(),
            'date' => Carbon::parse($this->created_at)->diffForHumans(),
            'status' => $this->status,
            'status_label' => $this->statuses[$this->status],
            'created_at' => $this->created_at,
            'opened_at' => Carbon::parse($this->created_at)->diffForHumans()
        ];
    }
}
