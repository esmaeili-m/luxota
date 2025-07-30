<?php
namespace Modules\Role\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;

class RoleRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Role::get();
    }

    public function getTrashedRoles(): \Illuminate\Database\Eloquent\Collection
    {
        return Role::onlyTrashed()->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Role::paginate($perPage);
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

    public function searchByFields(array $filters)
    {
        $query = Role::query();

        foreach ($filters as $field => $value) {
            if (empty($value)) continue;

            switch ($field) {
                case 'name':
                    $query->where('name', 'like', "%{$value}%");
                    break;

                case 'status':
                    $query->where('status', $value);
                    break;
            }
        }

        return $query->get();
    }
}
