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
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $categories = $this->service->getAll();
        return CategoryResource::collection($categories);
    }

    public function show($id): CategoryResource|\Illuminate\Http\JsonResponse
    {
        $category = $this->service->getById($id);
        if (!$category) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return new CategoryResource($category);
    }

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
