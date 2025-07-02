<?php

namespace Modules\Product\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\App\resources\ProductResource;
use Modules\Product\Services\ProductService;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints for managing Products"
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
     *     path="/api/v1/products",
     *     tags={"products"},
     *     summary="Get list of products",
     *     description="Returns a list of products",
     *     operationId="getProducts",
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
     * * Get all products with pagination
     *
     * @OA\Get(
     *     path="/api/v1/products",
     *     tags={"products"},
     *     summary="Get list of products with pagination",
     *     description="Returns a paginated list of products",
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
}
