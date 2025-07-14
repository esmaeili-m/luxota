<?php

namespace Modules\Referrer\App\resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReferrerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'total' => $this->collection->count(),
        ];
    }
} 