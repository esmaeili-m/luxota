<?php

namespace Modules\Product\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
            return [
                'id' => $this->id,
                'title'    => $this->title,
                'description' => $this->description,
                'slug' => $this->slug,
                'status' => $this->status,
                'image' => $this->image,
                'status_label' => $this->status == 1 ? 'active' : 'inactive',
                'version' => $this->version,
            ];
    }
}
