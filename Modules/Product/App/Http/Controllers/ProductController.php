<?php

namespace Modules\Product\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\App\Http\Requests\CreateProductRequest;
use Modules\Product\App\Http\Requests\UpdateProductRequest;
use Modules\Product\App\resources\ProductCollection;
use Modules\Product\App\resources\ProductResource;
use Modules\Product\Services\ProductService;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints for managing products"
 * )
 */
class ProductController extends Controller
{
    protected ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    /**
     * Get all products
     *
     * @OA\Get(
     *     path="/api/v1/products/all",
     *     tags={"Products"},
     *     summary="Get list of all products",
     *     description="Returns a list of all products with their categories",
     *     operationId="getAllProducts",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
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
        $products = $this->service->getAll();
        return ProductResource::collection($products);
    }

    /**
     * Get all products with pagination
     *
     * @OA\Get(
     *     path="/api/v1/products",
     *     tags={"Products"},
     *     summary="Get list of products with pagination",
     *     description="Returns a paginated list of products with their categories",
     *     operationId="getProducts",
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
     *                 @OA\Items(ref="#/components/schemas/Product")
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
    public function index(ProductService $service): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $products = $service->getPaginated();
        return ProductResource::collection($products);
    }

    /**
     * Get Product By ID
     *
     * @OA\Get(
     *     path="/api/v1/products/{id}",
     *     tags={"Products"},
     *     summary="Get a single product by ID",
     *     description="Returns a specific product based on the provided ID",
     *     operationId="getProductById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the product to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
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
    public function show($id): ProductResource|\Illuminate\Http\JsonResponse
    {
        $product = $this->service->getById($id);

        if (!$product) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new ProductResource($product);
    }

    /**
     * Get a Product by ID with its category
     *
     * @OA\Get(
     *     path="/api/v1/products/{id}/with-category",
     *     tags={"Products"},
     *     summary="Get product with its category",
     *     description="Returns a product by ID along with its category details",
     *     operationId="getProductWithCategory",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the product to retrieve along with its category",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
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
    public function showWithCategory($id): ProductResource|\Illuminate\Http\JsonResponse
    {
        $product = $this->service->getById($id, ['category']);

        if (!$product) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new ProductResource($product);
    }

    /**
     * Store a newly created product
     *
     * @OA\Post(
     *     path="/api/v1/products",
     *     tags={"Products"},
     *     summary="Create a new product",
     *     description="Creates a new product with the provided data",
     *     operationId="createProduct",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description","category_id"},
     *             @OA\Property(property="title", type="object",
     *                 @OA\Property(property="en", type="string", example="Product Name"),
     *                 @OA\Property(property="fa", type="string", example="نام محصول")
     *             ),
     *             @OA\Property(property="description", type="object",
     *                 @OA\Property(property="en", type="string", example="Product description"),
     *                 @OA\Property(property="fa", type="string", example="توضیحات محصول")
     *             ),
     *             @OA\Property(property="slug", type="string", example="product-name"),
     *             @OA\Property(property="last_version_update_date", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
     *             @OA\Property(property="version", type="number", format="float", example=1.0),
     *             @OA\Property(property="show_price", type="boolean", example=true),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
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
    public function store(CreateProductRequest $request): ProductResource
    {
        $data = $request->validated();

        // Transform the data structure for multilingual fields
        $data['title'] = [
            'en' => $data['title']['en'],
            'fa' => $data['title']['fa']
        ];

        $data['description'] = [
            'en' => $data['description']['en'],
            'fa' => $data['description']['fa']
        ];

        $product = $this->service->create($data);

        return new ProductResource($product);
    }

    /**
     * Update the specified product
     *
     * @OA\Put(
     *     path="/api/v1/products/{id}",
     *     tags={"Products"},
     *     summary="Update an existing product",
     *     description="Updates an existing product with the provided data",
     *     operationId="updateProduct",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the product to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="object",
     *                 @OA\Property(property="en", type="string", example="Updated Product Name"),
     *                 @OA\Property(property="fa", type="string", example="نام محصول به‌روزرسانی شده")
     *             ),
     *             @OA\Property(property="description", type="object",
     *                 @OA\Property(property="en", type="string", example="Updated product description"),
     *                 @OA\Property(property="fa", type="string", example="توضیحات محصول به‌روزرسانی شده")
     *             ),
     *             @OA\Property(property="order", type="integer", example=1),
     *             @OA\Property(property="last_version_update_date", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
     *             @OA\Property(property="version", type="number", format="float", example=1.1),
     *             @OA\Property(property="video_script", type="string", example="Video script content"),
     *             @OA\Property(property="show_price", type="boolean", example=true),
     *             @OA\Property(property="payment_type", type="boolean", example=true),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
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
    public function update($id, UpdateProductRequest $request): ProductResource|\Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $product = $this->service->update($id, $data);

        if (!$product) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new ProductResource($product);
    }

    /**
     * Remove the specified product
     *
     * @OA\Delete(
     *     path="/api/v1/products/{id}",
     *     tags={"Products"},
     *     summary="Delete a product",
     *     description="Soft deletes a product (moves to trash)",
     *     operationId="deleteProduct",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the product to delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Product deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
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

        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * Search products by various fields
     *
     * @OA\Get(
     *     path="/api/v1/products/search",
     *     tags={"Products"},
     *     summary="Search products",
     *     description="Search products by various fields",
     *     operationId="searchProducts",
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Search by title"
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Search by description"
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by category ID"
     *     ),
     *     @OA\Parameter(
     *         name="show_price",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean"),
     *         description="Filter by show price status"
     *     ),
     *     @OA\Parameter(
     *         name="version",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="number"),
     *         description="Filter by version"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ProductCollection")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function search(Request $request): ProductCollection
    {
        $filters = $request->all();
        $products = $this->service->searchByFields($filters);
        return new ProductCollection($products);
    }

    /**
     * Restore a soft deleted product
     *
     * @OA\Post(
     *     path="/api/v1/products/{id}/restore",
     *     tags={"Products"},
     *     summary="Restore a deleted product",
     *     description="Restores a soft deleted product from trash",
     *     operationId="restoreProduct",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the product to restore"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product restored successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Product restored successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
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
    public function restore($id)
    {
        $this->service->restoreProduct($id);
        return response()->json(['message' => 'Product restored successfully']);
    }

    /**
     * Permanently delete a product
     *
     * @OA\Delete(
     *     path="/api/v1/products/force-delete/{id}",
     *     tags={"Products"},
     *     summary="Permanently delete a product",
     *     description="Permanently deletes a product from the database",
     *     operationId="forceDeleteProduct",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the product to permanently delete"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product permanently deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Product permanently deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
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
    public function forceDelete($id)
    {
        $this->service->forceDeleteProduct($id);
        return response()->json(['message' => 'Product permanently deleted']);
    }

    /**
     * Get all trashed products
     *
     * @OA\Get(
     *     path="/api/v1/products/trash",
     *     tags={"Products"},
     *     summary="Get trashed products",
     *     description="Returns a list of all soft deleted products",
     *     operationId="getTrashedProducts",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function trash()
    {
        $products = $this->service->getTrashedProducts();
        return ProductResource::collection($products);
    }

    /**
     * Toggle product status
     *
     * @OA\Post(
     *     path="/api/v1/products/{id}/toggle-status",
     *     tags={"Products"},
     *     summary="Toggle product status",
     *     description="Toggles the status of a product between active and inactive",
     *     operationId="toggleProductStatus",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *         description="The ID of the product to toggle status"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status toggled successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Status toggled successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
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
    public function toggle_status($id)
    {
        $toggled = $this->service->toggle_status($id);

        if (!$toggled) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json(['message' => 'Status toggled successfully']);
    }
}
