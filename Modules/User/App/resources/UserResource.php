<?php

namespace Modules\User\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'description' => $this->description,
            'avatar' => asset('storage/'.$this->avatar),
            'websites' => $this->websites,
            'address' => $this->address,
            'luxota_website' => $this->luxota_website,
            'status' => $this->status,
            'status_label' => $this->status ? 'active' : 'inactive',
            'country_code' => $this->country_code,
            'whatsapp_country_code' => $this->whatsapp_country_code,
            'whatsapp_number' => $this->whatsapp_number,
            'role_id' => $this->role_id,
            'zone_id' => $this->zone_id,
            'city_id' => $this->city_id,
            'rank_id' => $this->rank_id,
            'referrer_id' => $this->referrer_id,
            'branch_id' => $this->branch_id,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            // Relationships
            'role' => $this->whenLoaded('role', function () {
                return [
                    'id' => $this->role->id,
                    'name' => $this->role->name,
                ];
            }),
            'zone' => $this->whenLoaded('zone', function () {
                return [
                    'id' => $this->zone->id,
                    'name' => $this->zone->name,
                ];
            }),
            'city' => $this->whenLoaded('city', function () {
                return [
                    'id' => $this->city->id,
                    'name' => $this->city->en,
                    'country' => $this->city?->country?->en,
                ];
            }),
            'rank' => $this->whenLoaded('rank', function () {
                return [
                    'id' => $this->rank->id,
                    'title' => $this->rank->title,
                ];
            }),
            'referrer' => $this->whenLoaded('referrer', function () {
                return [
                    'id' => $this->referrer->id,
                    'name' => $this->referrer->name,
                    'email' => $this->referrer->email,
                ];
            }),
            'branch' => $this->whenLoaded('branch', function () {
                return [
                    'id' => $this->branch->id,
                    'name' => $this->branch->name,
                ];
            }),
            'parent' => $this->whenLoaded('parent', function () {
                return [
                    'id' => $this->parent->id,
                    'name' => $this->parent->name,
                    'email' => $this->parent->email,
                ];
            }),
            'children' => $this->whenLoaded('children', function () {
                return $this->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'email' => $child->email,
                    ];
                });
            }),
            'referred_users' => $this->whenLoaded('referredUsers', function () {
                return $this->referredUsers->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ];
                });
            }),
        ];
    }
}
