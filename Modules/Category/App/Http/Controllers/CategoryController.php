<?php

namespace Modules\Category\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Category\App\Http\Requests\CreateCategoryRequest;
use Modules\Category\App\resources\CategoryResource;
use Modules\Category\Services\CategoryService;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="API Endpoints for managing categories"
 * )
 */
class CategoryController extends Controller
{
    protected CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }
    /**
     * Get all categories
     *
     * @OA\Get(
     *     path="/api/v1/categories",
     *     tags={"Categories"},
     *     summary="Get list of categories",
     *     description="Returns a list of categories",
     *     operationId="getCategories",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Category")
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
        $categories = $this->service->getAll();
        return CategoryResource::collection($categories);
    }
    /**
     * * Get all categories with pagination
     *
     * @OA\Get(
     *     path="/api/v1/categories",
     *     tags={"Categories"},
     *     summary="Get list of categories with pagination",
     *     description="Returns a paginated list of categories",
     *     operationId="getCategories",
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
     *                 @OA\Items(ref="#/components/schemas/Category")
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
    public function index(CategoryService $service): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $categories = $service->getPaginated();
        return CategoryResource::collection($categories);
    }
    /**
     * * * Get Category By ID
     *
     * @OA\Get(
     *     path="/api/v1/categories/{id}",
     *     tags={"Categories"},
     *     summary="Get a single category by ID",
     *     description="Returns a specific category based on the provided ID",
     *     operationId="getCategoryById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the category to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
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
    public function show($id): CategoryResource|\Illuminate\Http\JsonResponse
    {
        $category = $this->service->getById($id);
        if (!$category) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return new CategoryResource($category);
    }

    /**
     * * Create Category
     * @OA\Post(
     *     path="/api/v1/categories",
     *     tags={"Categories"},
     *     summary="Create a new category",
     *     description="Creates a new category ",
     *     operationId="createCategory",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "slug"},
     *             @OA\Property(property="title", type="object", @OA\AdditionalProperties(type="string")),
     *             @OA\Property(property="subtitle", type="object", nullable=true, @OA\AdditionalProperties(type="string")),
     *             @OA\Property(property="slug", type="string", example="electronics"),
     *             @OA\Property(property="image", type="string", format="file", nullable=true),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object", @OA\AdditionalProperties(type="array", items={"type": "string"}))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function store(CreateCategoryRequest $request): CategoryResource
    {
        $category = $this->service->create($request->validated());
        return new CategoryResource($category);
    }

    public function update($id, CreateCategoryRequest $request): CategoryResource|\Illuminate\Http\JsonResponse
    {
        $category = $this->service->update($id, $request->validated());
        if (!$category) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return new CategoryResource($category);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $category = $this->service->delete($id);
        if (!$category) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json(['message' => 'Deleted successfully']);
    }

}
