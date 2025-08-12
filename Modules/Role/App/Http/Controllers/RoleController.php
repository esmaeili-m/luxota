<?php

namespace Modules\Role\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Role\App\Http\Requests\CreateRoleRequest;
use Modules\Role\App\resources\RoleCollection;
use Modules\Role\App\resources\RoleResource;
use Modules\Role\Services\RoleService;

/**
 * @OA\Tag(
 *     name="Roles",
 *     description="API Endpoints for managing roles"
 * )
 */
class RoleController extends Controller
{
    protected RoleService $service;

    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }

    /**
     * Get all roles
     *
     * @OA\Get(
     *     path="/api/v1/roles",
     *     tags={"Roles"},
     *     summary="Get list of roles",
     *     description="Returns a list of roles",
     *     operationId="getRoles",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Role")
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
        $roles = $this->service->getAll();
        return RoleResource::collection($roles);
    }

    /**
     * Get all roles with pagination
     *
     * @OA\Get(
     *     path="/api/v1/roles",
     *     tags={"Roles"},
     *     summary="Get list of roles with pagination",
     *     description="Returns a paginated list of roles",
     *     operationId="getRoles",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", example=1),
     *         description="Page number"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Role")
     *             ),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $roles = $this->service->getPaginated();
        return RoleResource::collection($roles);
    }

    /**
     * Get Role By ID
     *
     * @OA\Get(
     *     path="/api/v1/roles/{id}",
     *     tags={"Roles"},
     *     summary="Get a single role by ID",
     *     description="Returns a specific role based on the provided ID",
     *     operationId="getRoleById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the role to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found",
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
    public function show($id): RoleResource|\Illuminate\Http\JsonResponse
    {
        $role = $this->service->getById($id);

        if (!$role) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new RoleResource($role);
    }

    /**
     * Create a new role
     *
     * @OA\Post(
     *     path="/api/v1/roles",
     *     tags={"Roles"},
     *     summary="Create a new role",
     *     description="Creates a new role with the provided data",
     *     operationId="createRole",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Admin"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
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
    public function store(CreateRoleRequest $request): RoleResource
    {
        $role = $this->service->create($request->validated());
        return new RoleResource($role);
    }

    /**
     * Update an existing role
     *
     * @OA\Put(
     *     path="/api/v1/roles/{id}",
     *     tags={"Roles"},
     *     summary="Update an existing role",
     *     description="Updates an existing role with the provided data",
     *     operationId="updateRole",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the role to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Admin"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function update($id, CreateRoleRequest $request): RoleResource|\Illuminate\Http\JsonResponse
    {
        $role = $this->service->update($id, $request->validated());

        if (!$role) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new RoleResource($role);
    }

    /**
     * Delete a role
     *
     * @OA\Delete(
     *     path="/api/v1/roles/{id}",
     *     tags={"Roles"},
     *     summary="Delete a role",
     *     description="Soft deletes a role",
     *     operationId="deleteRole",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the role to delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Role deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
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

        return response()->json(['message' => 'Role deleted successfully']);
    }

    /**
     * Search roles by fields
     *
     * @OA\Get(
     *     path="/api/v1/roles/search",
     *     tags={"Roles"},
     *     summary="Search roles",
     *     description="Search roles by various fields",
     *     operationId="searchRoles",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Search by name"
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean"),
     *         description="Filter by status"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/RoleCollection")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function search(Request $request): RoleCollection
    {
        $filters = $request->only(['name', 'status']);
        $roles = $this->service->searchByFields($filters);
        return new RoleCollection($roles);
    }

    /**
     * Restore a deleted role
     *
     * @OA\Post(
     *     path="/api/v1/roles/{id}/restore",
     *     tags={"Roles"},
     *     summary="Restore a deleted role",
     *     description="Restores a soft deleted role",
     *     operationId="restoreRole",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the role to restore"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role restored successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Role restored successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function restore($id): \Illuminate\Http\JsonResponse
    {
        $restored = $this->service->restoreRole($id);
        return response()->json(['message' => 'Role restored successfully']);
    }

    /**
     * Permanently delete a role
     *
     * @OA\Delete(
     *     path="/api/v1/roles/{id}/force",
     *     tags={"Roles"},
     *     summary="Permanently delete a role",
     *     description="Permanently deletes a role from the database",
     *     operationId="forceDeleteRole",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the role to permanently delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role permanently deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Role permanently deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function forceDelete($id): \Illuminate\Http\JsonResponse
    {
        $this->service->forceDeleteRole($id);
        return response()->json(['message' => 'Role permanently deleted']);
    }

    /**
     * Get trashed roles
     *
     * @OA\Get(
     *     path="/api/v1/roles/trash",
     *     tags={"Roles"},
     *     summary="Get trashed roles",
     *     description="Returns a list of soft deleted roles",
     *     operationId="getTrashedRoles",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Role")
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
        $roles = $this->service->getTrashedRoles();
        return RoleResource::collection($roles);
    }

    /**
     * Toggle role status
     *
     * @OA\Patch(
     *     path="/api/v1/roles/{id}/toggle-status",
     *     tags={"Roles"},
     *     summary="Toggle role status",
     *     description="Toggles the status of a role between active and inactive",
     *     operationId="toggleRoleStatus",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the role to toggle status"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status toggled successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function toggle_status($id): RoleResource|\Illuminate\Http\JsonResponse
    {
        $role = $this->service->toggle_status($id);
        if (!$role) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json(['message' => 'Change Status successfully']);
    }
}
