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
        return parent::toArray($request);
    }
}
