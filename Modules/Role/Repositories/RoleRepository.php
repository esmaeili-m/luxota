<?php
namespace Modules\Role\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Role\App\Models\Role;

class RoleRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Role::orderBy('title')->get();
    }

    public function getTrashedRoles(): \Illuminate\Database\Eloquent\Collection
    {
        return Role::onlyTrashed()->orderBy('title')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Role::orderBy('title')->paginate($perPage);
    }

    public function find(int $id, array $with = [])
    {
        return Role::with($with)->findOrFail($id);
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

    public function searchByFields(array $filters)
    {
        $query = Role::query();

        foreach ($filters as $field => $value) {
            if (empty($value)) continue;

            switch ($field) {
                case 'title':
                    $query->where('title', 'like', "%{$value}%");
                    break;

                case 'status':
                    $query->where('status', $value);
                    break;
            }
        }

        return $query->get();
    }
}
