<?php

namespace Modules\Rank\App\resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RankCollection extends ResourceCollection
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
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
            ],
        ];
    }
} 