<?php

namespace Modules\Referrer\App\Swagger;

/**
 * @OA\Schema(
 *     schema="Referrer",
 *     title="Referrer",
 *     description="Referrer model",
 *     @OA\Property(property="id", type="integer", example=1, description="Unique identifier"),
 *     @OA\Property(property="title", type="string", example="Website", description="Referrer title"),
 *     @OA\Property(property="status", type="boolean", example=true, description="Referrer status"),
 *     @OA\Property(property="status_label", type="string", example="active", description="Human readable status"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
 * )
 */
class ReferrerSwagger
{
    // This class is used for Swagger documentation only
} 