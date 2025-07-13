<?php

namespace Modules\Role\App\Swagger;

/**
 * @OA\Schema(
 *     schema="Role",
 *     title="Role",
 *     description="Role model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The unique identifier of the role",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="The title of the role",
 *         example="Admin"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="boolean",
 *         description="The status of the role (true for active, false for inactive)",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="status_label",
 *         type="string",
 *         description="Human readable status label",
 *         example="active"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="The timestamp when the role was created",
 *         example="2024-01-01T00:00:00.000000Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="The timestamp when the role was last updated",
 *         example="2024-01-01T00:00:00.000000Z"
 *     ),
 *     @OA\Property(
 *         property="deleted_at",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         description="The timestamp when the role was soft deleted",
 *         example=null
 *     )
 * )
 */
class RoleSchema
{
    // This class is used only for OpenAPI documentation
} 