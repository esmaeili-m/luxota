<?php
namespace Modules\User\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\User\App\Models\User;

class UserRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return User::with(['role', 'zone', 'city', 'rank', 'referrer', 'branch', 'parent'])->get();
    }

    public function getParentUsers(): \Illuminate\Database\Eloquent\Collection
    {
        return User::where('status',1)->where('role_id',1)->whereNull('parent_id')->select('name','email','id')->get();
    }

    public function getUsersByRoleName($role, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = User::with(['role', 'zone', 'city', 'rank', 'referrer', 'branch', 'parent'])->where('role_id',$role->id);

        foreach ($filters as $field => $value) {

            if ($value === null || $value === '') continue;

            switch ($field) {
                case 'name':
                    $query->where('name', 'like', "%{$value}%");
                    break;

                case 'email':
                    $query->where('email', 'like', "%{$value}%");
                    break;

                case 'phone':
                    $query->where('phone', 'like', "%{$value}%");
                    break;

                case 'status':
                    $query->where('status', $value);
                    break;

                case 'role_id':
                    $query->where('role_id', $value);
                    break;

                case 'zone_id':
                    $query->where('zone_id', $value);
                    break;

                case 'city_id':
                    $query->where('city_id', $value);
                    break;

                case 'rank_id':
                    $query->where('rank_id', $value);
                    break;

                case 'branch_id':
                    $query->where('branch_id', $value);
                    break;
            }
        }

        return $query->paginate($perPage);
    }

    public function getTrashedUsers(): \Illuminate\Database\Eloquent\Collection
    {
        return User::with(['role', 'zone', 'city', 'rank', 'referrer', 'branch', 'parent'])->onlyTrashed()->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return User::with(['role', 'zone', 'city', 'rank', 'referrer', 'branch', 'parent'])->paginate($perPage);
    }
    public function getUsers(array $filters = [] , $perPage = 15, $paginate = false)
    {
        $query = User::query();
        if (!empty($filters)) {
            $query->search($filters);
        }
        if ($paginate) {
            return $query->paginate($perPage);
        } else {
            return $query->get();
        }
    }
    public function find(int $id, array $with = [])
    {
        return User::with(['city.country','role','parent'])->findOrFail($id);
    }

    public function findTrashedById(int $id)
    {
        return User::withTrashed()->find($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function restore(User $user)
    {
        return $user->restore();
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
    }

    public function searchByFields(array $filters)
    {
        $query = User::with(['role', 'zone', 'city', 'rank', 'referrer', 'branch', 'parent']);

        foreach ($filters as $field => $value) {


            if ($value === null || $value === '') continue;

            switch ($field) {
                case 'name':
                    $query->where('name', 'like', "%{$value}%");
                    break;

                case 'email':
                    $query->where('email', 'like', "%{$value}%");
                    break;

                case 'phone':
                    $query->where('phone', 'like', "%{$value}%");
                    break;

                case 'status':
                    $query->where('status', $value);
                    break;

                case 'role_id':
                    $query->where('role_id', $value);
                    break;

                case 'zone_id':
                    $query->where('zone_id', $value);
                    break;

                case 'city_id':
                    $query->where('city_id', $value);
                    break;

                case 'rank_id':
                    $query->where('rank_id', $value);
                    break;

                case 'branch_id':
                    $query->where('branch_id', $value);
                    break;
            }
        }

        return $query->get();
    }
}
