<?php

namespace Modules\Branch\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Branch\App\Http\Requests\CreateBranchRequest;
use Modules\Branch\App\Http\Requests\UpdateBranchRequest;
use Modules\Branch\App\resources\BranchCollection;
use Modules\Branch\App\resources\BranchResource;
use Modules\Branch\Services\BranchService;

/**
 * @OA\Tag(
 *     name="Branches",
 *     description="API Endpoints for managing branches"
 * )
 */
class BranchController extends Controller
{
    protected BranchService $service;

    public function __construct(BranchService $service)
    {
        $this->service = $service;
    }

    /**
     * Get all branches with pagination
     *
     * @OA\Get(
     *     path="/api/v1/branches",
     *     tags={"Branches"},
     *     summary="Get list of branches with pagination",
     *     description="Returns a paginated list of branches",
     *     operationId="getBranches",
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
     *                 @OA\Items(ref="#/components/schemas/Branch")
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
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $branches = $this->service->getBranches($request->only(['status','title','per_page','paginate']));
        return BranchResource::collection($branches);
    }

    /**
     * Get Branch By ID
     *
     * @OA\Get(
     *     path="/api/v1/branches/{id}",
     *     tags={"Branches"},
     *     summary="Get a single branch by ID",
     *     description="Returns a specific branch based on the provided ID",
     *     operationId="getBranchById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the branch to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Branch")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Branch not found",
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
    public function show($id): BranchResource|\Illuminate\Http\JsonResponse
    {
        $branch = $this->service->getById($id);

        if (!$branch) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new BranchResource($branch);
    }

    /**
     * Create a new branch
     *
     * @OA\Post(
     *     path="/api/v1/branches",
     *     tags={"Branches"},
     *     summary="Create a new branch",
     *     description="Creates a new branch with the provided data",
     *     operationId="createBranch",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Main Branch"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Branch created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Branch")
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
    public function store(CreateBranchRequest $request): BranchResource
    {
        $branch = $this->service->create($request->validated());
        return new BranchResource($branch);
    }

    /**
     * Update an existing branch
     *
     * @OA\Put(
     *     path="/api/v1/branches/{id}",
     *     tags={"Branches"},
     *     summary="Update an existing branch",
     *     description="Updates an existing branch with the provided data",
     *     operationId="updateBranch",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the branch to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Central Branch"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Branch updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Branch")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Branch not found",
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
    public function update($id, UpdateBranchRequest $request): BranchResource|\Illuminate\Http\JsonResponse
    {
        $branch = $this->service->update($id, $request->validated());

        if (!$branch) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new BranchResource($branch);
    }

    /**
     * Delete a branch
     *
     * @OA\Delete(
     *     path="/api/v1/branches/{id}",
     *     tags={"Branches"},
     *     summary="Delete a branch",
     *     description="Deletes a branch by ID",
     *     operationId="deleteBranch",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the branch to delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Branch deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Branch deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Branch not found",
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

        return response()->json(['message' => 'Branch deleted successfully']);
    }

    /**
     * Restore a deleted branch
     *
     * @OA\Post(
     *     path="/api/v1/branches/{id}/restore",
     *     tags={"Branches"},
     *     summary="Restore a deleted branch",
     *     description="Restores a soft-deleted branch",
     *     operationId="restoreBranch",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the branch to restore"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Branch restored successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Branch restored successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Branch not found",
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
        $restored = $this->service->restoreBranch($id);

        if (!$restored) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json(['message' => 'Branch restored successfully']);
    }

    /**
     * Force delete a branch
     *
     * @OA\Delete(
     *     path="/api/v1/branches/force-delete/{id}",
     *     tags={"Branches"},
     *     summary="Force delete a branch",
     *     description="Permanently deletes a branch from the database",
     *     operationId="forceDeleteBranch",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the branch to permanently delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Branch permanently deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Branch permanently deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Branch not found",
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
            $this->service->forceDeleteBranch($id);
            return response()->json(['message' => 'Branch permanently deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }

    /**
     * Get trashed branches
     *
     * @OA\Get(
     *     path="/api/v1/branches/trash",
     *     tags={"Branches"},
     *     summary="Get trashed branches",
     *     description="Returns a list of soft-deleted branches",
     *     operationId="getTrashedBranches",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Branch")
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
        $branches = $this->service->getTrashedBranches();
        return BranchResource::collection($branches);
    }

}
