<?php

namespace Modules\ActivityLog\App\resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ActivityLogCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
