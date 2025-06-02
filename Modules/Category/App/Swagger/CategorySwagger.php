<?php

namespace Modules\Category\App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Category",
 *     title="Category",
 *     description="Model Category"
 * )
 */
class CategorySwagger
{
    /**
     * @OA\Property(property="id", type="integer", example=1, description="Unique identifier of the category")
     */
    public $id;

    /**
     * @OA\Property(
     *     property="title",
     *     type="object",
     *     description="Localized title of the category",
     *     @OA\AdditionalProperties(type="string"),
     *     example={"en": "Electronics", "fa": "الکترونیک"}
     * )
     */
    public $title;

    /**
     * @OA\Property(
     *     property="slug",
     *     type="string",
     *     example="electronics",
     *     description="URL-friendly version of the title"
     * )
     */
    public $slug;

    /**
     * @OA\Property(
     *     property="subtitle",
     *     type="object",
     *     description="Localized subtitle of the category",
     *     @OA\AdditionalProperties(type="string"),
     *     example={"en": "All electronics products", "fa": "محصولات الکترونیکی"}
     * )
     */
    public $subtitle;

    /**
     * @OA\Property(
     *     property="image",
     *     type="string",
     *     example="https://example.com/images/electronics.jpg",
     *     description="URL to the category image"
     * )
     */
    public $image;

    /**
     * @OA\Property(
     *     property="parent_id",
     *     type="integer",
     *     nullable=true,
     *     example=3,
     *     description="ID of parent category if exists"
     * )
     */
    public $parent_id;

    /**
     * @OA\Property(
     *     property="status",
     *     type="boolean",
     *     example=true,
     *     description="Status of the category (active/inactive)"
     * )
     */
    public $status;

}
