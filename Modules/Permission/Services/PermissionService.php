<?php
namespace Modules\Permission\Services;

use Modules\Permission\Repositories\PermissionRepository;

class PermissionService
{
    public PermissionRepository $repo;

    public function __construct(PermissionRepository $repo)
    {
        $this->repo= $repo;
    }
    public function get_permissions()
    {
        return $this->repo->get_permissions();
    }
    public function getGroupedPermissions(): array
    {
        $permissions= $this->repo->get_permissions();
        return $permissions
            ->groupBy(function ($permission) {
                return explode('.', $permission->name)[0]; // user, product, ...
            })
            ->map(function ($items, $groupKey) {
                return [
                    'key' => $groupKey,
                    'title' => $this->resolveTitle($groupKey),
                    'permissions' => $items->map(function ($permission) {
                        return [
                            'id' => $permission->id,
                            'name' => $permission->name,
                            'action' => explode('.', $permission->name)[1] ?? null,
                        ];
                    })->values()
                ];
            })
            ->values()
            ->toArray();
    }

    private function resolveTitle(string $key): string
    {
        return match ($key) {
            'user' => 'User Management',
            'role' => 'Role Management',
            'product' => 'Product Management',
            'category' => 'Category Management',
            'voucher' => 'Voucher Management',
            default => ucfirst($key) . ' Management',
        };
    }
}
