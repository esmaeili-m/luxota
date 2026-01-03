<?php

namespace Modules\Referrer\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Referrer\App\Http\Requests\CreateReferrerRequest;
use Modules\Referrer\App\Http\Requests\UpdateReferrerRequest;
use Modules\Referrer\App\resources\ReferrerCollection;
use Modules\Referrer\App\resources\ReferrerResource;
use Modules\Referrer\Services\ReferrerService;

/**
 * @OA\Tag(
 *     name="Referrers",
 *     description="API Endpoints for managing referrers"
 * )
 */
class ReferrerController extends Controller
{
    protected ReferrerService $service;

    public function __construct(ReferrerService $service)
    {
        $this->service = $service;
    }

    /**
     * Get all referrers with pagination
     *
     * @OA\Get(
     *     path="/api/v1/referrers",
     *     tags={"Referrers"},
     *     summary="Get list of referrers with pagination",
     *     description="Returns a paginated list of referrers",
     *     operationId="getReferrers",
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
     *                 @OA\Items(ref="#/components/schemas/Referrer")
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
        $referrers = $this->service->getReferrers($request->only(['status','title','per_page','paginate']));
        return ReferrerResource::collection($referrers);
    }

    /**
     * Get Referrer By ID
     *
     * @OA\Get(
     *     path="/api/v1/referrers/{id}",
     *     tags={"Referrers"},
     *     summary="Get a single referrer by ID",
     *     description="Returns a specific referrer based on the provided ID",
     *     operationId="getReferrerById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the referrer to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Referrer")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Referrer not found",
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
    public function show($id): ReferrerResource|\Illuminate\Http\JsonResponse
    {
        $referrer = $this->service->getById($id);

        if (!$referrer) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new ReferrerResource($referrer);
    }

    /**
     * Create a new referrer
     *
     * @OA\Post(
     *     path="/api/v1/referrers",
     *     tags={"Referrers"},
     *     summary="Create a new referrer",
     *     description="Creates a new referrer with the provided data",
     *     operationId="createReferrer",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Website Referral"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Referrer created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Referrer")
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
    public function store(CreateReferrerRequest $request): ReferrerResource
    {
        $referrer = $this->service->create($request->validated());
        return new ReferrerResource($referrer);
    }

    /**
     * Update an existing referrer
     *
     * @OA\Put(
     *     path="/api/v1/referrers/{id}",
     *     tags={"Referrers"},
     *     summary="Update an existing referrer",
     *     description="Updates an existing referrer with the provided data",
     *     operationId="updateReferrer",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the referrer to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Updated Website Referral"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Referrer updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Referrer")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Referrer not found",
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
    public function update($id, UpdateReferrerRequest $request): ReferrerResource|\Illuminate\Http\JsonResponse
    {
        $referrer = $this->service->update($id, $request->validated());

        if (!$referrer) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new ReferrerResource($referrer);
    }

    /**
     * Delete a referrer
     *
     * @OA\Delete(
     *     path="/api/v1/referrers/{id}",
     *     tags={"Referrers"},
     *     summary="Delete a referrer",
     *     description="Soft deletes a referrer by ID",
     *     operationId="deleteReferrer",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the referrer to delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Referrer deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Referrer deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Referrer not found",
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

        return response()->json(['message' => 'Referrer deleted successfully']);
    }


    /**
     * Restore a deleted referrer
     *
     * @OA\Post(
     *     path="/api/v1/referrers/{id}/restore",
     *     tags={"Referrers"},
     *     summary="Restore a deleted referrer",
     *     description="Restores a soft deleted referrer",
     *     operationId="restoreReferrer",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the referrer to restore"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Referrer restored successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Referrer restored successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Referrer not found",
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
        $restored = $this->service->restoreReferrer($id);

        if (!$restored) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json(['message' => 'Referrer restored successfully']);
    }

    /**
     * Force delete a referrer
     *
     * @OA\Delete(
     *     path="/api/v1/referrers/force-delete/{id}",
     *     tags={"Referrers"},
     *     summary="Force delete a referrer",
     *     description="Permanently deletes a referrer from the database",
     *     operationId="forceDeleteReferrer",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the referrer to permanently delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Referrer permanently deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Referrer permanently deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Referrer not found",
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
            $this->service->forceDeleteReferrer($id);
            return response()->json(['message' => 'Referrer permanently deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }

    /**
     * Get trashed referrers
     *
     * @OA\Get(
     *     path="/api/v1/referrers/trash",
     *     tags={"Referrers"},
     *     summary="Get trashed referrers",
     *     description="Returns a list of soft deleted referrers",
     *     operationId="getTrashedReferrers",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Referrer")
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
        $referrers = $this->service->getTrashedReferrers();
        return ReferrerResource::collection($referrers);
    }

}
