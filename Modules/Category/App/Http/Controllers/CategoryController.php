<?php

namespace Modules\Category\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Category\App\Http\Requests\CreateCategoryRequest;
use Modules\Category\App\resources\CategoryCollection;
use Modules\Category\App\resources\CategoryResource;
use Modules\Category\App\resources\CategoryWithProductsResource;
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
     * Get a Category by ID with its children
     *
     * @OA\Get(
     *     path="/api/v1/categories/{id}/with-children",
     *     tags={"Categories"},
     *     summary="Get category with its children",
     *     description="Returns a category by ID along with its immediate child categories",
     *     operationId="getCategoryWithChildren",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the category to retrieve along with its children",
     *         @OA\Schema(type="integer", example=1)
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
    public function showWithChildren($id): CategoryResource|\Illuminate\Http\JsonResponse
    {
        $category = $this->service->getById($id, ['children']);

        if (!$category) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new CategoryResource($category);
    }

    /**
     * Get a category by ID with its parent
     *
     * @OA\Get(
     *     path="/api/v1/categories/{id}/with-parent",
     *     tags={"Categories"},
     *     summary="Get category with its parent",
     *     description="Returns a category by ID along with its parent category",
     *     operationId="getCategoryWithParent",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the category to retrieve along with its parent",
     *         @OA\Schema(type="integer", example=1)
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
    public function showWithParent($id): CategoryResource|\Illuminate\Http\JsonResponse
    {
        $category = $this->service->getById($id, ['parent']);
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

    /**
     * Update Category
     * @OA\Put(
     *     path="/api/v1/categories/{id}",
     *     tags={"Categories"},
     *     summary="Update an existing category",
     *     description="Updates a category by ID",
     *     operationId="updateCategory",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of category to update",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
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
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Not found")
     *         )
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
    public function update($id, CreateCategoryRequest $request): CategoryResource|\Illuminate\Http\JsonResponse
    {
        $category = $this->service->update($id, $request->validated());
        if (!$category) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return new CategoryResource($category);
    }

    /**
     * Delete Category
     * @OA\Delete(
     *     path="/api/v1/categories/{id}",
     *     tags={"Categories"},
     *     summary="Delete a category by ID",
     *     description="Deletes the specified category",
     *     operationId="deleteCategory",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of category to delete",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Not found")
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
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $category = $this->service->delete($id);
        if (!$category) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json(['message' => 'Deleted successfully']);
    }

    /**
     * Search Categories
     * @OA\Get(
     *     path="/api/v1/categories/search",
     *     tags={"Categories"},
     *     summary="Search categories by filters",
     *     description="Returns categories filtered by title, subtitle, slug, or status",
     *     operationId="searchCategories",
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Filter by category title (partial match)",
     *         required=false,
     *         @OA\Schema(type="string", example="php")
     *     ),
     *     @OA\Parameter(
     *         name="subtitle",
     *         in="query",
     *         description="Filter by category subtitle (partial match)",
     *         required=false,
     *         @OA\Schema(type="string", example="tutorial")
     *     ),
     *     @OA\Parameter(
     *         name="slug",
     *         in="query",
     *         description="Filter by category slug (partial match)",
     *         required=false,
     *         @OA\Schema(type="string", example="electronics")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by category status",
     *         required=false,
     *         @OA\Schema(type="string", example="active")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Filtered categories list",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Category")
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
    public function search(Request $request): CategoryCollection
    {
        $filters = $request->only(['title', 'subtitle', 'slug', 'status']);

        return new CategoryCollection(
            $this->service->searchByFields($filters)
        );
    }

    /**
     * Restore a deleted category
     * @OA\Patch(
     *     path="/api/v1/categories/{id}/restore",
     *     tags={"Categories"},
     *     summary="Restore a soft-deleted category",
     *     description="Restores a category that was previously soft-deleted",
     *     operationId="restoreCategory",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category to restore",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category restored successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category restored successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function restore($id)
    {
        $this->service->restoreCategory($id);
        return response()->json(['message' => 'Category restored successfully']);
    }

    /**
     * Permanently delete a category
     * @OA\Delete(
     *     path="/api/v1/categories/{id}/force",
     *     tags={"Categories"},
     *     summary="Permanently delete a category",
     *     description="Deletes a category permanently from the database",
     *     operationId="forceDeleteCategory",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category to permanently delete",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category permanently deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category permanently deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function forceDelete($id)
    {
        $this->service->forceDeleteCategory($id);
        return response()->json(['message' => 'Category permanently deleted']);
    }

    /**
     * List trashed categories
     * @OA\Get(
     *     path="/api/v1/categories/trash",
     *     tags={"Categories"},
     *     summary="List soft-deleted categories",
     *     description="Returns a list of categories that are soft-deleted (in trash)",
     *     operationId="getTrashedCategories",
     *     @OA\Response(
     *         response=200,
     *         description="List of trashed categories",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Category")
     *         )
     *     )
     * )
     */
    public function trash()
    {
        $categories = $this->service->getTrashedCategories();
        return CategoryResource::collection($categories);
    }

    /**
     * Toggle category status
     * @OA\Patch(
     *     path="/api/v1/categories/{id}/toggle-status",
     *     tags={"Categories"},
     *     summary="Toggle category active/inactive status",
     *     description="Changes the status of a category between active and inactive",
     *     operationId="toggleCategoryStatus",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category to toggle status",
     *         required=true,
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status changed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Change Status successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function toggle_status($id)
    {
        $this->service->toggle_status($id);
        return response()->json(['message' => 'Change Status successfully']);
    }

    /**
     * Get category with its children
     * @OA\Get(
     *     path="/api/v1/categories/{id}/children",
     *     tags={"Categories"},
     *     summary="Get a category and its children",
     *     description="Returns the specified category and a list of its child categories",
     *     operationId="getCategoryChildren",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the parent category",
     *         required=true,
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category and children data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="category", ref="#/components/schemas/Category"),
     *             @OA\Property(
     *                 property="children",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Category")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function category_children($id)
    {
        $data = [];
        $data['category'] = $this->service->getById($id);
        $data['children'] = $this->service->getChildrenCategory($id);
        return response()->json($data);
    }

    public function find_by_slug($slug):CategoryWithProductsResource
    {
        $data = $this->service->getCategoryWithProducts($slug);
        return new CategoryWithProductsResource($data);
    }
}
