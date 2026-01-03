<?php

namespace Modules\Role\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;
use Modules\Role\Repositories\RoleRepository;

class RoleService
{
    protected RoleRepository $repo;

    public function __construct(RoleRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getRoles(array $params)
    {
        $filters = [
            'status' => $params['status'] ?? null,
            'name' => $params['name'] ?? null,
        ];
        $perPage = $params['per_page'] ?? 15;
        $paginate = $params['paginate'] ?? true;
        return $this->repo->getRoles($filters, $perPage);
    }

    public function getTrashedRoles(): \Illuminate\Pagination\LengthAwarePaginator
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

}
