<?php

namespace Modules\City\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'country_id'  => $this->country_id,
            'country'     => $this->whenLoaded('country', function () {
                return [
                    'id' => $this->country->id,
                    'name' => $this->country->en ?? $this->country->name,
                ];
            }),
            'en'          => $this->en,
            'abb'         => $this->abb,
            'status'      => $this->status,
            'status_label'=> $this->status == 1 ? 'active' : 'inactive',
            'fa'          => $this->fa,
            'ar'          => $this->ar,
            'ku'          => $this->ku,
            'tr'          => $this->tr,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'deleted_at'  => $this->deleted_at,
        ];
    }
}
