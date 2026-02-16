<?php

namespace Modules\Planner\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'color' => $this->color,
            'parent_id' => $this->parent_id,
        ];
    }
}
