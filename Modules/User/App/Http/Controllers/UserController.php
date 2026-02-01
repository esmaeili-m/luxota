<?php

namespace Modules\User\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Branch\App\Models\Branch;
use Modules\Country\App\Models\Country;
use Modules\Rank\App\Models\Rank;
use Modules\Referrer\App\Models\Referrer;
use Modules\Role\App\resources\RoleResource;
use Modules\User\App\Http\Requests\CreateUserRequest;
use Modules\User\App\Models\User;
use Modules\User\App\resources\UserCollection;
use Modules\User\App\resources\UserResource;
use Modules\User\Services\UserService;
use Modules\Zone\App\Models\Zone;
use Modules\Zone\App\resources\ZoneResource;
use Spatie\Permission\Models\Role;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for managing users"
 * )
 */
class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Get user form data
     *
     * @OA\Get(
     *     path="/api/v1/users/form-data",
     *     tags={"Users"},
     *     summary="Get user form data",
     *     description="Returns form data required for creating/editing users including roles, zones, cities, ranks, etc.",
     *     operationId="getUserFormData",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="roles", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="zones", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="cities", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="ranks", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="branches", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="referrers", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="countries", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function UserFormData()
    {
        return response()->json(
            $this->service->getFormData()
        );
    }

    /**
     * Get all users
     *
     * @OA\Get(
     *     path="/api/v1/users/all",
     *     tags={"Users"},
     *     summary="Get all users",
     *     description="Returns a complete list of all users without pagination",
     *     operationId="getAllUsers",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function all(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $users = $this->service->getAll();
        return UserResource::collection($users);
    }

    /**
     * Get all users with pagination
     *
     * @OA\Get(
     *     path="/api/v1/users",
     *     tags={"Users"},
     *     summary="Get list of users with pagination",
     *     description="Returns a paginated list of users",
     *     operationId="getUsersPaginated",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", example=1),
     *         description="Page number"
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", example=15),
     *         description="Number of items per page"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/User")
     *             ),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="total", type="integer"),
     *             @OA\Property(property="per_page", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function index1(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $users = $this->service->getPaginated();
        return UserResource::collection($users);
    }

    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $users = $this->service->getUsers($request->only(['status','name','per_page','paginate']));
        return UserResource::collection($users);
    }

    /**
     * Get users by role name with pagination
     *
     * @OA\Get(
     *     path="/api/v1/users/by-role/{role}",
     *     tags={"Users"},
     *     summary="Get users by role name",
     *     description="Returns paginated list of users filtered by role name with optional filters",
     *     operationId="getUsersByRoleName",
     *     @OA\Parameter(
     *         name="role",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", example="admin"),
     *         description="Role name to filter users by"
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Filter by user name"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Filter by email"
     *     ),
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Filter by phone number"
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean"),
     *         description="Filter by user status"
     *     ),
     *     @OA\Parameter(
     *         name="zone_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by zone ID"
     *     ),
     *     @OA\Parameter(
     *         name="city_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by city ID"
     *     ),
     *     @OA\Parameter(
     *         name="rank_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by rank ID"
     *     ),
     *     @OA\Parameter(
     *         name="branch_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by branch ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="role", ref="#/components/schemas/Role"),
     *             @OA\Property(
     *                 property="users",
     *                 type="object",
     *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Role not found")
     *         )
     *     )
     * )
     */
    public function indexByRoleName($role, Request $request): JsonResponse
    {
        $filters = $request->only([
            'name',
            'email',
            'phone',
            'status',
            'zone_id',
            'city_id',
            'rank_id',
            'branch_id'
        ]);
        $data = $this->service->paginateUsersByRoleName($role, $filters);
        return response()->json([
            'role' => new RoleResource($data['role']),
            'users' => UserResource::collection($data['users'])->response()->getData(true),
        ]);
    }

    /**
     * Get User By ID
     *
     * @OA\Get(
     *     path="/api/v1/users/{id}",
     *     tags={"Users"},
     *     summary="Get a single user by ID",
     *     description="Returns a specific user based on the provided ID",
     *     operationId="getUserById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the user to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function show($id): UserResource|\Illuminate\Http\JsonResponse
    {
        $user = $this->service->getById($id);

        if (!$user) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return new UserResource($user);
    }

    /**
     * Get users by current user's role
     *
     * @OA\Get(
     *     path="/api/v1/users/by-role",
     *     tags={"Users"},
     *     summary="Get users by current user's role",
     *     description="Returns list of users filtered by the current authenticated user's role",
     *     operationId="getUsersByRole",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function findByRole()
    {
        $users = $this->service->getUsersByRole();
        return UserResource::collection($users);
    }
    /**
     * Create a new user
     *
     * @OA\Post(
     *     path="/api/v1/users",
     *     tags={"Users"},
     *     summary="Create a new user",
     *     description="Creates a new user with the provided data",
     *     operationId="createUser",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "phone"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="description", type="string", example="User description"),
     *             @OA\Property(property="avatar", type="string", example="avatar.jpg"),
     *             @OA\Property(property="website", type="object"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="luxota_website", type="string", example="https://example.com"),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="country_code", type="string", example="US"),
     *             @OA\Property(property="whatsapp_number", type="string", example="+1234567890"),
     *             @OA\Property(property="role_id", type="integer", example=1),
     *             @OA\Property(property="zone_id", type="integer", example=1),
     *             @OA\Property(property="city_id", type="integer", example=1),
     *             @OA\Property(property="rank_id", type="integer", example=2),
     *             @OA\Property(property="referrer_id", type="integer", example=1),
     *             @OA\Property(property="branch_id", type="integer", example=1),
     *             @OA\Property(property="parent_id", type="integer", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function store(CreateUserRequest $request): UserResource
    {
        $user = $this->service->create($request->validated());
        return new UserResource($user);
    }

    /**
     * Update an existing user
     *
     * @OA\Put(
     *     path="/api/v1/users/{id}",
     *     tags={"Users"},
     *     summary="Update an existing user",
     *     description="Updates an existing user with the provided data",
     *     operationId="updateUser",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the user to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="description", type="string", example="User description"),
     *             @OA\Property(property="avatar", type="string", example="avatar.jpg"),
     *             @OA\Property(property="website", type="object"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="luxota_website", type="string", example="https://example.com"),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="country_code", type="string", example="US"),
     *             @OA\Property(property="whatsapp_number", type="string", example="+1234567890"),
     *             @OA\Property(property="role_id", type="integer", example=1),
     *             @OA\Property(property="zone_id", type="integer", example=1),
     *             @OA\Property(property="city_id", type="integer", example=1),
     *             @OA\Property(property="rank_id", type="integer", example=2),
     *             @OA\Property(property="referrer_id", type="integer", example=1),
     *             @OA\Property(property="branch_id", type="integer", example=1),
     *             @OA\Property(property="parent_id", type="integer", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function update($id, CreateUserRequest $request): UserResource|\Illuminate\Http\JsonResponse
    {

        $user = $this->service->update($id, $request->validated());

        if (!$user) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new UserResource($user);
    }

    /**
     * Delete a user
     *
     * @OA\Delete(
     *     path="/api/v1/users/{id}",
     *     tags={"Users"},
     *     summary="Delete a user",
     *     description="Deletes a user by ID",
     *     operationId="deleteUser",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the user to delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="User deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Search users
     *
     * @OA\Get(
     *     path="/api/v1/users/search",
     *     tags={"Users"},
     *     summary="Search users",
     *     description="Search users by various fields",
     *     operationId="searchUsers",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Search by name"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Search by email"
     *     ),
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Search by phone"
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean"),
     *         description="Filter by status"
     *     ),
     *     @OA\Parameter(
     *         name="role_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by role ID"
     *     ),
     *     @OA\Parameter(
     *         name="zone_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by zone ID"
     *     ),
     *     @OA\Parameter(
     *         name="city_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by city ID"
     *     ),
     *     @OA\Parameter(
     *         name="rank_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by rank ID"
     *     ),
     *     @OA\Parameter(
     *         name="branch_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by branch ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/User")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function search(Request $request): UserCollection
    {
        $filters = $request->only([
            'name',
            'email',
            'phone',
            'status',
            'role_id',
            'zone_id',
            'city_id',
            'rank_id',
            'branch_id'
        ]);
        $users = $this->service->searchByFields($filters);

        return new UserCollection($users);
    }

    /**
     * Restore a deleted user
     *
     * @OA\Post(
     *     path="/api/v1/users/{id}/restore",
     *     tags={"Users"},
     *     summary="Restore a deleted user",
     *     description="Restores a soft-deleted user",
     *     operationId="restoreUser",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the user to restore"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User restored successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="User restored successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function restore($id): \Illuminate\Http\JsonResponse
    {
        $restored = $this->service->restoreUser($id);

        if (!$restored) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json(['message' => 'User restored successfully']);
    }

    /**
     * Force delete a user
     *
     * @OA\Delete(
     *     path="/api/v1/users/force-delete/{id}",
     *     tags={"Users"},
     *     summary="Force delete a user",
     *     description="Permanently deletes a user from the database",
     *     operationId="forceDeleteUser",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the user to permanently delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User permanently deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="User permanently deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function forceDelete($id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->service->forceDeleteUser($id);
            return response()->json(['message' => 'User permanently deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }

    /**
     * Get trashed users
     *
     * @OA\Get(
     *     path="/api/v1/users/trash",
     *     tags={"Users"},
     *     summary="Get trashed users",
     *     description="Returns a list of soft-deleted users",
     *     operationId="getTrashedUsers",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function trash(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $users = $this->service->getTrashedUsers();
        return UserResource::collection($users);
    }

    /**
     * Toggle user status
     *
     * @OA\Post(
     *     path="/api/v1/users/{id}/toggle-status",
     *     tags={"Users"},
     *     summary="Toggle user status",
     *     description="Toggles the status of a user between active and inactive",
     *     operationId="toggleUserStatus",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the user to toggle status"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status changed successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function toggle_status($id): UserResource|\Illuminate\Http\JsonResponse
    {
        $user = $this->service->getById($id);
        if (!$user) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $this->service->toggle_status($id);
        return new UserResource($user->fresh());
    }
}
