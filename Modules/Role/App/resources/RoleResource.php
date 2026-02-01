<?php

namespace Modules\Role\App\resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'status'       => $this->status,
            'status_label' => $this->status == 1 ? 'active' : 'inactive',
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
            'deleted_at'   => $this->deleted_at ? $this->deleted_at->format('Y / m / d') : null,
            'permissions'  => $this->whenLoaded('permissions', function () {
                return $this->permissions->map(function ($permission) {
                    return [
                        'id'         => $permission->id,
                        'name'       => $permission->name,
                        'guard_name' => $permission->guard_name,
                        'created_at' => $permission->created_at,
                        'updated_at' => $permission->updated_at,
                    ];
                });
            }),
        ];
    }
}
