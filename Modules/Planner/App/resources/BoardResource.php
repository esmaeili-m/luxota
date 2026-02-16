<?php

namespace Modules\Planner\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BoardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'key'         => $this->key,
            'description' => $this->description,
            'type'        => $this->type,
            'status'      => $this->status,
            'owner_type'  => $this->owner_type,
            'parent_id'   => $this->parent_id,
            'owner_id'    => $this->owner_id,
            'visibility'  => $this->visibility,
            'columns' => ColumnResource::collection(
                $this->whenLoaded('columns', function () {
                    return $this->columns->sortBy('order');
                })
            ),

            'created_by'  => $this->created_by,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }

}
