<?php

namespace Modules\User\App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Your API Title",
 *     version="1.0.0",
 *     description="API documentation for your Laravel project"
 * )
 */
/**
 * @OA\Schema(
 *     schema="User",
 *     required={"id", "name", "email", "phone", "password"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="phone", type="string", example="+989123456789"),
 *     @OA\Property(property="password", type="string", format="password", example="secret123"),
 *     @OA\Property(property="role_id", type="integer", example=1),
 *     @OA\Property(property="zone_id", type="integer", example=2),
 *     @OA\Property(property="description", type="string", nullable=true, example="توضیحات اضافی کاربر"),
 *     @OA\Property(property="city_id", type="integer", example=112),
 *     @OA\Property(property="avatar", type="string", nullable=true, example="https://example.com/avatar.jpg"),
 *     @OA\Property(property="website", type="object", nullable=true, example={"en": "https://site.com", "fa": "https://site.ir"}),
 *     @OA\Property(property="remember_token", type="string", nullable=true, example="1|some-token-here"),
 *     @OA\Property(property="address", type="string", nullable=true, example="تهران، ایران"),
 *     @OA\Property(property="rank_id", type="integer", example=2),
 *     @OA\Property(property="referrer_id", type="integer", example=1),
 *     @OA\Property(property="branche_id", type="integer", example=3),
 *     @OA\Property(property="luxota_website", type="string", nullable=true, example="https://luxota.ir"),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=5),
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="country_code", type="string", nullable=true, example="+98"),
 *     @OA\Property(property="whatsapp_number", type="string", nullable=true, example="9123456789")
 * )
 */
class UserSwagger {}
