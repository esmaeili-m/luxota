<?php

namespace Modules\Planner\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\App\resources\UserResource;

class TaskLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'task_type' => $this->task_type,
            'task_id' => $this->task_id,

            'from_board' => new BoardResource($this->whenLoaded('fromBoard')),
            'to_board' => new BoardResource($this->whenLoaded('toBoard')),

            'from_column' => new ColumnResource($this->whenLoaded('fromColumn')),
            'to_column' => new ColumnResource($this->whenLoaded('toColumn')),

            'user' => new UserResource($this->whenLoaded('user')),

            'started_at' => $this->started_at?->toDateTimeString(),
            'ended_at' => $this->ended_at?->toDateTimeString(),
            'duration_minutes' => $this->duration_minutes,
            'note' => $this->note,

            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
