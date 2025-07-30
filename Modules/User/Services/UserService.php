<?php

namespace Modules\User\Services;

use App\Services\Uploader;
use Modules\Role\Services\RoleService;
use Modules\User\App\Models\User;
use Modules\User\Repositories\UserRepository;

class UserService
{
    protected UserRepository $repo;
    protected RoleService $roleService;

    public function __construct(UserRepository $repo,RoleService $roleService)
    {
        $this->repo = $repo;
        $this->roleService = $roleService;

    }

    public function getPaginated(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repo->paginate($perPage);
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->all();
    }
    public function paginateUsersByRoleName(string $role, array $filters = [])
    {
        $role = $this->roleService->findByName($role);
        $users = $this->repo->getUsersByRoleName($role, $filters);
        return [
            'role' => $role,
            'users' => $users,
        ];
    }

    public function getTrashedUsers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->getTrashedUsers();
    }

    public function getById(int $id, array $with = [])
    {
        return $this->repo->find($id, $with);
    }

    public function getUsersByRole(string $role)
    {
        return $this->repo->getAllUsersByRoleWithPaginate($role);

    }

    public function create(array $data)
    {

        if (isset($data['avatar'])) {
            $data['avatar'] = Uploader::uploadImage($data['avatar'], 'users');
        }
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): ?User
    {
        $user = $this->repo->find($id);

        if (!$user) {
            return null;
        }
        if (isset($data['avatar'])) {
            $data['avatar'] = Uploader::uploadImage($data['avatar'], 'users');
        }
        $this->repo->update($user, $data);

        return $user->fresh();
    }

    public function delete(int $id): bool
    {
        $user = $this->repo->find($id);
        if (!$user) {
            return false;
        }
        return $user->delete();
    }

    public function searchByFields(array $filters): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->repo->searchByFields($filters);
    }

    public function restoreUser($id)
    {
        $user = $this->repo->findTrashedById($id);
        if (!$user) {
            return false;
        }
        return $this->repo->restore($user);
    }

    public function forceDeleteUser($id)
    {
        $this->repo->forceDelete($id);
    }

    public function toggle_status($id)
    {
        $user = $this->repo->find($id);
        $newStatus = !$user->status;
        $this->repo->update($user, ['status' => $newStatus]);
        return response()->json(['message' => 'Change Status successfully']);
    }
}
