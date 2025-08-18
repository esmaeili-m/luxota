<?php

namespace Modules\Price\App\resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PriceResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
