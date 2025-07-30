<?php

namespace Modules\Role\Services;

use Spatie\Permission\Models\Role;
use Modules\Role\Repositories\RoleRepository;

class RoleService
{
    protected RoleRepository $repo;

    public function __construct(RoleRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getPaginated(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repo->paginate($perPage);
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->all();
    }

    public function getTrashedRoles(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->getTrashedRoles();
    }

    public function getById(int $id, array $with = [])
    {
        return $this->repo->find($id, $with);
    }

    public function findByName(string $name): Role
    {
        return $this->repo->findByName($name);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): ?Role
    {
        $role = $this->repo->find($id);

        if (!$role) {
            return null;
        }

        $this->repo->update($role, $data);

        return $role->fresh();
    }

    public function delete(int $id): bool
    {
        $role = $this->repo->find($id);
        if (!$role) {
            return false;
        }
        return $role->delete();
    }

    public function searchByFields(array $filters): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->repo->searchByFields($filters);
    }

    public function restoreRole($id)
    {
        $role = $this->repo->findTrashedById($id);
        if (!$role) {
            return false;
        }
        return $this->repo->restore($role);
    }

    public function forceDeleteRole($id)
    {
        $this->repo->forceDelete($id);
    }

    public function toggle_status($id)
    {
        $role = $this->repo->find($id);
        $newStatus = !$role->status;
        $this->repo->update($role, ['status' => $newStatus]);
        return response()->json(['message' => 'Change Status successfully']);

    }
}
