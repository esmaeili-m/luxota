<?php

namespace Modules\Country\App\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'en' => $this->en,
            'abb' => $this->abb,
            'fa' => $this->fa,
            'ar' => $this->ar,
            'ku' => $this->ku,
            'tr' => $this->tr,
            'phone_code' => $this->phone_code,
            'flag' => $this->flag,
            'zone_id' => $this->zone_id,
            'currency_id' => $this->currency_id,
            'status' => $this->status,
            'display_name' => $this->display_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 