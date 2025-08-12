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
            'parent' => $this->whenLoaded('parent', function () {
                return [
                    'id' => $this->parent->id,
                    'title' => $this->parent->title,
                ];
            }),
            'children' => $this->whenLoaded('children', function () {
                return $this->children->map(function ($child) {
                    return [
                        'id'          => $child->id,
                        'title'       => $child->title,
                        'subtitle'    => $child->subtitle,
                        'slug'        => $child->slug,
                        'order'       => $child->order,
                        'image'       => $child->image,
                        'parent_id'   => $child->parent_id,
                        'status'      => $child->status,
                        'status_label'=> $child->status == 1 ? 'active' : 'inactive',
                    ];
                });
            }),
        ];
    }
}
