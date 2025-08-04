<?php

namespace Modules\ActivityLog\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'log_name'=> $this->log_name,
            'description'=> $this->description,
            'event'=>$this->event,
            'user_id' =>$this->causer_id,
            'user' => $this->whenLoaded('user', function () {
                     return [
                        'id' => $this->user->id,
                        'name' => $this->user->name,
                    ];
            }),
            'data'=>$this->properties,
            'created_at' => $this->created_at
        ];
    }
}
