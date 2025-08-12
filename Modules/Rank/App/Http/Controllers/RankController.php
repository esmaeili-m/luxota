<?php

namespace Modules\Rank\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Rank\App\Http\Requests\CreateRankRequest;
use Modules\Rank\App\resources\RankCollection;
use Modules\Rank\App\resources\RankResource;
use Modules\Rank\Services\RankService;

/**
 * @OA\Tag(
 *     name="Ranks",
 *     description="API Endpoints for managing ranks"
 * )
 */
class RankController extends Controller
{
    protected RankService $service;

    public function __construct(RankService $service)
    {
        $this->service = $service;
    }

    /**
     * Get all ranks
     *
     * @OA\Get(
     *     path="/api/v1/ranks/all",
     *     tags={"Ranks"},
     *     summary="Get all ranks",
     *     description="Returns a list of all ranks",
     *     operationId="getAllRanks",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Rank")
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
        $ranks = $this->service->getAll();
        return RankResource::collection($ranks);
    }

    /**
     * Get all ranks with pagination
     *
     * @OA\Get(
     *     path="/api/v1/ranks",
     *     tags={"Ranks"},
     *     summary="Get list of ranks with pagination",
     *     description="Returns a paginated list of ranks",
     *     operationId="getRanks",
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
     *                 @OA\Items(ref="#/components/schemas/Rank")
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
        $ranks = $this->service->getPaginated();
        return RankResource::collection($ranks);
    }

    /**
     * Get Rank By ID
     *
     * @OA\Get(
     *     path="/api/v1/ranks/{id}",
     *     tags={"Ranks"},
     *     summary="Get a single rank by ID",
     *     description="Returns a specific rank based on the provided ID",
     *     operationId="getRankById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the rank to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Rank")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rank not found",
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
    public function show($id): RankResource|\Illuminate\Http\JsonResponse
    {
        $rank = $this->service->getById($id);

        if (!$rank) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new RankResource($rank);
    }

    /**
     * Create a new rank
     *
     * @OA\Post(
     *     path="/api/v1/ranks",
     *     tags={"Ranks"},
     *     summary="Create a new rank",
     *     description="Creates a new rank with the provided data",
     *     operationId="createRank",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Manager"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rank created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Rank")
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
    public function store(CreateRankRequest $request): RankResource
    {
        $rank = $this->service->create($request->validated());
        return new RankResource($rank);
    }

    /**
     * Update an existing rank
     *
     * @OA\Put(
     *     path="/api/v1/ranks/{id}",
     *     tags={"Ranks"},
     *     summary="Update an existing rank",
     *     description="Updates an existing rank with the provided data",
     *     operationId="updateRank",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the rank to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Senior Manager"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rank updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Rank")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rank not found",
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
    public function update($id, CreateRankRequest $request): RankResource|\Illuminate\Http\JsonResponse
    {
        $rank = $this->service->update($id, $request->validated());

        if (!$rank) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new RankResource($rank);
    }

    /**
     * Delete a rank
     *
     * @OA\Delete(
     *     path="/api/v1/ranks/{id}",
     *     tags={"Ranks"},
     *     summary="Delete a rank",
     *     description="Deletes a rank by ID",
     *     operationId="deleteRank",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the rank to delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rank deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Rank deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rank not found",
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

        return response()->json(['message' => 'Rank deleted successfully']);
    }

    /**
     * Search ranks
     *
     * @OA\Get(
     *     path="/api/v1/ranks/search",
     *     tags={"Ranks"},
     *     summary="Search ranks",
     *     description="Search ranks by various criteria",
     *     operationId="searchRanks",
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Search by title"
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
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Rank")
     *             ),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function search(Request $request): RankCollection
    {
        $filters = $request->only(['title', 'status']);
        $ranks = $this->service->searchByFields($filters);
        return new RankCollection($ranks);
    }

    /**
     * Restore a deleted rank
     *
     * @OA\Post(
     *     path="/api/v1/ranks/{id}/restore",
     *     tags={"Ranks"},
     *     summary="Restore a deleted rank",
     *     description="Restores a soft-deleted rank",
     *     operationId="restoreRank",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the rank to restore"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rank restored successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Rank restored successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rank not found",
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
        $restored = $this->service->restoreRank($id);

        if (!$restored) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json(['message' => 'Rank restored successfully']);
    }

    /**
     * Force delete a rank
     *
     * @OA\Delete(
     *     path="/api/v1/ranks/force-delete/{id}",
     *     tags={"Ranks"},
     *     summary="Force delete a rank",
     *     description="Permanently deletes a rank from the database",
     *     operationId="forceDeleteRank",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the rank to permanently delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rank permanently deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Rank permanently deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rank not found",
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
            $this->service->forceDeleteRank($id);
            return response()->json(['message' => 'Rank permanently deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }

    /**
     * Get trashed ranks
     *
     * @OA\Get(
     *     path="/api/v1/ranks/trash",
     *     tags={"Ranks"},
     *     summary="Get trashed ranks",
     *     description="Returns a list of soft-deleted ranks",
     *     operationId="getTrashedRanks",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Rank")
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
        $ranks = $this->service->getTrashedRanks();
        return RankResource::collection($ranks);
    }

    /**
     * Toggle rank status
     *
     * @OA\Post(
     *     path="/api/v1/ranks/{id}/toggle-status",
     *     tags={"Ranks"},
     *     summary="Toggle rank status",
     *     description="Toggles the status of a rank between active and inactive",
     *     operationId="toggleRankStatus",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the rank to toggle status"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status changed successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Change Status successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rank not found",
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
    public function toggle_status($id): RankResource|\Illuminate\Http\JsonResponse
    {
        try {
            $this->service->toggle_status($id);
            $rank = $this->service->getById($id);
            return new RankResource($rank);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }
}
