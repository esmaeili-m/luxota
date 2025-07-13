<?php

namespace Modules\Category\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'subtitle'    => $this->subtitle,
            'slug'        => $this->slug,
            'order'       => $this->order,
            'image'       => $this->image,
            'parent_id'   => $this->parent_id,
            'status'      => $this->status,
            'status_label'=> $this->status == 1 ? 'active' : 'inactive',
            'parent' => new CategoryResource($this->whenLoaded('parent')),
            'children' => CategoryResource::collection($this->whenLoaded('children')),
        ];
    }
}
