<?php

/**
 * @OA\Schema(
 *     schema="Product",
 *     title="Product",
 *     description="Product model",
 *     @OA\Property(property="id", type="integer", example=1, description="Product ID"),
 *     @OA\Property(property="title", type="object", 
 *         @OA\Property(property="en", type="string", example="Product Name"),
 *         @OA\Property(property="fa", type="string", example="نام محصول")
 *     ),
 *     @OA\Property(property="description", type="object",
 *         @OA\Property(property="en", type="string", example="Product description"),
 *         @OA\Property(property="fa", type="string", example="توضیحات محصول")
 *     ),
 *     @OA\Property(property="product_code", type="integer", example=1001, description="Product code"),
 *     @OA\Property(property="last_version_update_date", type="string", format="date-time", example="2024-01-01T00:00:00Z", description="Last version update date"),
 *     @OA\Property(property="version", type="number", format="float", example=1.0, description="Product version"),
 *     @OA\Property(property="image", type="string", example="products/image.jpg", description="Product image path"),
 *     @OA\Property(property="video_script", type="string", example="Sample video script", description="Product video script"),
 *     @OA\Property(property="slug", type="string", example="product-name", description="Product slug"),
 *     @OA\Property(property="order", type="integer", example=1, description="Product order"),
 *     @OA\Property(property="show_price", type="boolean", example=true, description="Show price flag"),
 *     @OA\Property(property="payment_type", type="boolean", example=true, description="Payment type flag"),
 *     @OA\Property(property="status", type="boolean", example=true, description="Product status"),
 *     @OA\Property(property="status_label", type="string", example="active", description="Product status label"),
 *     @OA\Property(property="category_id", type="integer", example=1, description="Category ID"),
 *     @OA\Property(property="category", ref="#/components/schemas/Category", description="Product category"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z")
 * )
 */

/**
 * @OA\Schema(
 *     schema="ProductCollection",
 *     title="Product Collection",
 *     description="Collection of products",
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Product")),
 *     @OA\Property(property="meta", type="object",
 *         @OA\Property(property="total", type="integer", example=100),
 *         @OA\Property(property="per_page", type="integer", example=15),
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="last_page", type="integer", example=7)
 *     )
 * )
 */ 