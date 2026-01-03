<?php
namespace Modules\Role\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;

class RoleRepository
{

    public function getTrashedRoles($perPage = 15):LengthAwarePaginator
    {
        return Role::onlyTrashed()->paginate($perPage);
    }

    public function getRoles(array $filters = [] , $perPage = 15, $paginate = false)
    {
        $query = Role::query();
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }
        if ($paginate) {
            return $query->paginate($perPage);
        } else {
            return $query->get();
        }
    }

    public function find(int $id, array $with = [])
    {
        return Role::with($with)->findOrFail($id);
    }

    public function findByName(string $name): Role
    {
        return Role::where('name', $name)->firstOrFail();
    }

    public function findTrashedById(int $id)
    {
        return Role::withTrashed()->find($id);
    }

    public function create(array $data)
    {
        return Role::create($data);
    }

    public function update(Role $role, array $data): bool
    {
        return $role->update($data);
    }

    public function delete(Role $role): bool
    {
        return $role->delete();
    }

    public function restore(Role $role)
    {
        return $role->restore();
    }

    public function forceDelete($id)
    {
        $role = Role::onlyTrashed()->findOrFail($id);
        $role->forceDelete();
    }

}
