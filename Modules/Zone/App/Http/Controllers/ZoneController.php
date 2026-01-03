<?php

namespace Modules\Zone\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Zone\App\Http\Requests\CreateZoneRequest;
use Modules\Zone\App\Http\Requests\UpdateZoneRequest;
use Modules\Zone\App\resources\ZoneCollection;
use Modules\Zone\App\resources\ZoneResource;
use Modules\Zone\Services\ZoneService;

/**
 * @OA\Tag(
 *     name="Zones",
 *     description="API Endpoints for managing zones"
 * )
 */
class ZoneController extends Controller
{
    protected ZoneService $service;

    public function __construct(ZoneService $service)
    {
        $this->service = $service;
    }

    /**
     * Get all zones with pagination
     *
     * @OA\Get(
     *     path="/api/v1/zones",
     *     tags={"Zones"},
     *     summary="Get list of zones with pagination",
     *     description="Returns a paginated list of zones",
     *     operationId="getZones",
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
     *                 @OA\Items(ref="#/components/schemas/Zone")
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
        $input = $request->only(['status','title','description','per_page','paginate']);
        $input['paginate'] = $request->boolean('paginate');
        $zones = $this->service->getZones($input);
        return ZoneResource::collection($zones);
    }

    /**
     * Get Zone By ID
     *
     * @OA\Get(
     *     path="/api/v1/zones/{id}",
     *     tags={"Zones"},
     *     summary="Get a single zone by ID",
     *     description="Returns a specific zone based on the provided ID",
     *     operationId="getZoneById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the zone to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Zone")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Zone not found",
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
    public function show($id): ZoneResource|\Illuminate\Http\JsonResponse
    {
        $zone = $this->service->getById($id);

        if (!$zone) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new ZoneResource($zone);
    }

    /**
     * Create a new zone
     *
     * @OA\Post(
     *     path="/api/v1/zones",
     *     tags={"Zones"},
     *     summary="Create a new zone",
     *     description="Creates a new zone with the provided data",
     *     operationId="createZone",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="North Zone"),
     *             @OA\Property(property="description", type="string", example="Northern region of the city"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Zone created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Zone")
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
    public function store(CreateZoneRequest $request): ZoneResource
    {
        $zone = $this->service->create($request->validated());
        return new ZoneResource($zone);
    }

    /**
     * Update an existing zone
     *
     * @OA\Put(
     *     path="/api/v1/zones/{id}",
     *     tags={"Zones"},
     *     summary="Update an existing zone",
     *     description="Updates an existing zone with the provided data",
     *     operationId="updateZone",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the zone to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Updated Zone"),
     *             @OA\Property(property="description", type="string", example="Updated zone description"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Zone updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Zone")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Zone not found",
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
    public function update($id, UpdateZoneRequest $request): ZoneResource|\Illuminate\Http\JsonResponse
    {
        $zone = $this->service->update($id, $request->validated());

        if (!$zone) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new ZoneResource($zone);
    }

    /**
     * Delete a zone
     *
     * @OA\Delete(
     *     path="/api/v1/zones/{id}",
     *     tags={"Zones"},
     *     summary="Delete a zone",
     *     description="Soft deletes a zone",
     *     operationId="deleteZone",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the zone to delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Zone deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Zone deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Zone not found"
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

        return response()->json(['message' => 'Zone deleted successfully']);
    }

    /**
     * Restore a deleted zone
     *
     * @OA\Post(
     *     path="/api/v1/zones/{id}/restore",
     *     tags={"Zones"},
     *     summary="Restore a deleted zone",
     *     description="Restores a soft deleted zone",
     *     operationId="restoreZone",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the zone to restore"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Zone restored successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Zone restored successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Zone not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function restore($id): \Illuminate\Http\JsonResponse
    {
        $restored = $this->service->restoreZone($id);

        if (!$restored) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json(['message' => 'Zone restored successfully']);
    }

    /**
     * Force delete a zone
     *
     * @OA\Delete(
     *     path="/api/v1/zones/force-delete/{id}",
     *     tags={"Zones"},
     *     summary="Force delete a zone",
     *     description="Permanently deletes a zone",
     *     operationId="forceDeleteZone",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the zone to permanently delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Zone permanently deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Zone permanently deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Zone not found"
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
            $this->service->forceDeleteZone($id);
            return response()->json(['message' => 'Zone permanently deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }

    /**
     * Get trashed zones
     *
     * @OA\Get(
     *     path="/api/v1/zones/trash",
     *     tags={"Zones"},
     *     summary="Get trashed zones",
     *     description="Returns a list of soft deleted zones",
     *     operationId="getTrashedZones",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Zone")
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
        $zones = $this->service->getTrashedZones();
        return ZoneResource::collection($zones);
    }

}
