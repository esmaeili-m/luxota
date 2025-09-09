<?php

namespace Modules\Category\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\App\resources\ProductResource;

class CategoryWithProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'    => $this['category']->id,
            'title'  => $this['category']->title,
            'subtitle'  => $this['category']->subtitle,
            'slug'  => $this['category']->slug,
            'products' => ProductResource::collection($this['products']),
        ];
    }
}
