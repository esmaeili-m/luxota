<?php

namespace Modules\Planner\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ColumnResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'board_id'  => $this->board_id,
            'title'     => $this->title,
            'key'       => $this->key,
            'order'     => $this->order,
            'wip_limit' => $this->wip_limit,
            'is_start'  => $this->is_start,
            'is_end'    => $this->is_end,
            'status'    => $this->status,

            'tasks' => TaskResource::collection(
                $this->whenLoaded('tasks')
            ),
        ];
    }
}
