<?php

namespace Modules\User\App\Swagger;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="description", type="string", example="User description"),
 *     @OA\Property(property="avatar", type="string", example="avatar.jpg"),
 *     @OA\Property(property="website", type="object", example={"website": "https://example.com", "instagram": "john_doe", "telegram": "johndoe"}),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="luxota_website", type="string", example="https://luxota.com/john"),
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="status_label", type="string", example="active"),
 *     @OA\Property(property="country_code", type="string", example="US"),
 *     @OA\Property(property="whatsapp_number", type="string", example="+1234567890"),
 *     @OA\Property(property="role_id", type="integer", example=1),
 *     @OA\Property(property="zone_id", type="integer", example=1),
 *     @OA\Property(property="city_id", type="integer", example=1),
 *     @OA\Property(property="rank_id", type="integer", example=2),
 *     @OA\Property(property="referrer_id", type="integer", example=1),
 *     @OA\Property(property="branch_id", type="integer", example=1),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(
 *         property="role",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="title", type="string", example="Admin")
 *     ),
 *     @OA\Property(
 *         property="zone",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="North Zone")
 *     ),
 *     @OA\Property(
 *         property="city",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Tehran")
 *     ),
 *     @OA\Property(
 *         property="rank",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=2),
 *         @OA\Property(property="title", type="string", example="Manager")
 *     ),
 *     @OA\Property(
 *         property="referrer",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Admin User"),
 *         @OA\Property(property="email", type="string", example="admin@luxota.com")
 *     ),
 *     @OA\Property(
 *         property="branch",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Main Branch")
 *     ),
 *     @OA\Property(
 *         property="parent",
 *         type="object",
 *         nullable=true,
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Parent User"),
 *         @OA\Property(property="email", type="string", example="parent@luxota.com")
 *     ),
 *     @OA\Property(
 *         property="children",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=2),
 *             @OA\Property(property="name", type="string", example="Child User"),
 *             @OA\Property(property="email", type="string", example="child@luxota.com")
 *         )
 *     ),
 *     @OA\Property(
 *         property="referred_users",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=3),
 *             @OA\Property(property="name", type="string", example="Referred User"),
 *             @OA\Property(property="email", type="string", example="referred@luxota.com")
 *         )
 *     )
 * )
 */
class UserSwagger
{
    // This class is used for Swagger documentation only
}
