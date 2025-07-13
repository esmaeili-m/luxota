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
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'product_code' => $this->product_code,
            'last_version_update_date' => $this->last_version_update_date?->format('Y-m-d H:i:s'),
            'version'     => $this->version,
            'image'       => $this->image,
            'video_script' => $this->video_script,
            'slug'        => $this->slug,
            'order'       => $this->order,
            'show_price'  => $this->show_price,
            'payment_type' => $this->payment_type,
            'status'      => $this->status,
            'status_label'=> $this->status ? 'active' : 'inactive',
            'category_id' => $this->category_id,
            'category'    => new \Modules\Category\App\resources\CategoryResource($this->whenLoaded('category')),
            'created_at'  => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'  => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
