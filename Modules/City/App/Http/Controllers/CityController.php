<?php

namespace Modules\City\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\City\App\Http\Requests\CreateCityRequest;
use Modules\City\App\resources\CityCollection;
use Modules\City\App\resources\CityResource;
use Modules\City\Services\CityService;

/**
 * @OA\Tag(
 *     name="Cities",
 *     description="API Endpoints for managing cities"
 * )
 */
class CityController extends Controller
{
    protected CityService $service;

    public function __construct(CityService $service)
    {
        $this->service = $service;
    }

    /**
     * Get all cities
     *
     * @OA\Get(
     *     path="/api/v1/cities/all",
     *     tags={"Cities"},
     *     summary="Get all cities",
     *     description="Returns a list of all cities",
     *     operationId="getAllCities",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/City")
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
        $cities = $this->service->getAll();
        return CityResource::collection($cities);
    }

    /**
     * Get all cities with pagination
     *
     * @OA\Get(
     *     path="/api/v1/cities",
     *     tags={"Cities"},
     *     summary="Get list of cities with pagination",
     *     description="Returns a paginated list of cities",
     *     operationId="getCities",
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
     *                 @OA\Items(ref="#/components/schemas/City")
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
        $cities = $this->service->getPaginated();
        return CityResource::collection($cities);
    }

    /**
     * Get City By ID
     *
     * @OA\Get(
     *     path="/api/v1/cities/{id}",
     *     tags={"Cities"},
     *     summary="Get a single city by ID",
     *     description="Returns a specific city based on the provided ID",
     *     operationId="getCityById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the city to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/City")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="City not found",
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
    public function show($id): CityResource|\Illuminate\Http\JsonResponse
    {
        $city = $this->service->getById($id);

        if (!$city) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new CityResource($city);
    }

    /**
     * Create a new city
     *
     * @OA\Post(
     *     path="/api/v1/cities",
     *     tags={"Cities"},
     *     summary="Create a new city",
     *     description="Creates a new city with the provided data",
     *     operationId="createCity",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"country_id", "en", "abb", "priority"},
     *             @OA\Property(property="country_id", type="integer", example=1),
     *             @OA\Property(property="en", type="string", example="Tehran"),
     *             @OA\Property(property="abb", type="string", example="THR"),
     *             @OA\Property(property="priority", type="integer", example=1),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="fa", type="string", example="تهران"),
     *             @OA\Property(property="ar", type="string", example="طهران"),
     *             @OA\Property(property="ku", type="string", example="تههران"),
     *             @OA\Property(property="tr", type="string", example="Tahran")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="City created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/City")
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
    public function store(CreateCityRequest $request): CityResource
    {
        $city = $this->service->create($request->validated());
        return new CityResource($city);
    }

    /**
     * Update an existing city
     *
     * @OA\Put(
     *     path="/api/v1/cities/{id}",
     *     tags={"Cities"},
     *     summary="Update an existing city",
     *     description="Updates an existing city with the provided data",
     *     operationId="updateCity",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the city to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"country_id", "en", "abb", "priority"},
     *             @OA\Property(property="country_id", type="integer", example=1),
     *             @OA\Property(property="en", type="string", example="Tehran"),
     *             @OA\Property(property="abb", type="string", example="THR"),
     *             @OA\Property(property="priority", type="integer", example=1),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="fa", type="string", example="تهران"),
     *             @OA\Property(property="ar", type="string", example="طهران"),
     *             @OA\Property(property="ku", type="string", example="تههران"),
     *             @OA\Property(property="tr", type="string", example="Tahran")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="City updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/City")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="City not found",
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
    public function update($id, CreateCityRequest $request): CityResource|\Illuminate\Http\JsonResponse
    {
        $city = $this->service->update($id, $request->validated());

        if (!$city) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new CityResource($city);
    }

    /**
     * Delete a city
     *
     * @OA\Delete(
     *     path="/api/v1/cities/{id}",
     *     tags={"Cities"},
     *     summary="Delete a city",
     *     description="Deletes a city by ID",
     *     operationId="deleteCity",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the city to delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="City deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="City deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="City not found",
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

        return response()->json(['message' => 'City deleted successfully']);
    }

    /**
     * Search cities
     *
     * @OA\Get(
     *     path="/api/v1/cities/search",
     *     tags={"Cities"},
     *     summary="Search cities",
     *     description="Search cities by various criteria",
     *     operationId="searchCities",
     *     @OA\Parameter(
     *         name="en",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Search by English name"
     *     ),
     *     @OA\Parameter(
     *         name="abb",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Search by abbreviation"
     *     ),
     *     @OA\Parameter(
     *         name="country_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by country ID"
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean"),
     *         description="Filter by status"
     *     ),
     *     @OA\Parameter(
     *         name="priority",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by priority"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/City")
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
    public function search(Request $request): CityCollection
    {
        $filters = $request->only(['en', 'abb', 'country_id', 'status', 'priority']);
        $cities = $this->service->searchByFields($filters);
        return new CityCollection($cities);
    }

    /**
     * Restore a deleted city
     *
     * @OA\Post(
     *     path="/api/v1/cities/{id}/restore",
     *     tags={"Cities"},
     *     summary="Restore a deleted city",
     *     description="Restores a soft-deleted city",
     *     operationId="restoreCity",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the city to restore"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="City restored successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="City restored successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="City not found",
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
        $restored = $this->service->restoreCity($id);

        if (!$restored) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json(['message' => 'City restored successfully']);
    }

    /**
     * Force delete a city
     *
     * @OA\Delete(
     *     path="/api/v1/cities/force-delete/{id}",
     *     tags={"Cities"},
     *     summary="Force delete a city",
     *     description="Permanently deletes a city from the database",
     *     operationId="forceDeleteCity",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the city to permanently delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="City permanently deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="City permanently deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="City not found",
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
            $this->service->forceDeleteCity($id);
            return response()->json(['message' => 'City permanently deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }

    /**
     * Get trashed cities
     *
     * @OA\Get(
     *     path="/api/v1/cities/trash",
     *     tags={"Cities"},
     *     summary="Get trashed cities",
     *     description="Returns a list of soft-deleted cities",
     *     operationId="getTrashedCities",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/City")
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
        $cities = $this->service->getTrashedCities();
        return CityResource::collection($cities);
    }

    /**
     * Toggle city status
     *
     * @OA\Post(
     *     path="/api/v1/cities/{id}/toggle-status",
     *     tags={"Cities"},
     *     summary="Toggle city status",
     *     description="Toggles the status of a city between active and inactive",
     *     operationId="toggleCityStatus",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the city to toggle status"
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
     *         description="City not found",
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
    public function toggle_status($id): CityResource|\Illuminate\Http\JsonResponse
    {
        try {
            $this->service->toggle_status($id);
            $city = $this->service->getById($id);
            return new CityResource($city);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }
}
