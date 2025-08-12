<?php

namespace Modules\User\App\resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->collection->count(),
                'per_page' => $this->perPage ?? null,
                'current_page' => $this->currentPage ?? null,
                'last_page' => $this->lastPage ?? null,
                'from' => $this->firstItem ?? null,
                'to' => $this->lastItem ?? null,
            ],
        ];
    }
} 