<?php

namespace Modules\Price\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
          'zone_id' => $this->zone_id,
          'product_id' => $this->product_id,
          'price' => $this->price,
          'zone' => $this->whenLoaded('zone', function () {
                return [
                    'id' => $this->zone->id,
                    'name' => $this->zone->name,
                ];
            }),
            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'title' => $this->product->title['en'] ?? 'UNKNOW',
                ];
            }),
        ];
    }
}
