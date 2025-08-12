<?php

namespace Modules\Category\App\resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray($request): array
    {
        return $this->collection->map(function ($item) {
            return [
                'id' => $item->id,
                'title'    => $item->title,
                'subtitle' => $item->subtitle,
                'slug' => $item->slug,
                'status' => $item->status,
                'status_label' => $item->status == 1 ? 'active' : 'inactive',
                'created_at' => $item->created_at,
            ];
        });
    }
}
