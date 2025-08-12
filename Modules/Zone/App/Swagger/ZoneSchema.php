<?php

namespace Modules\Zone\App\Swagger;

/**
 * @OA\Schema(
 *     schema="Zone",
 *     title="Zone",
 *     description="Zone model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The unique identifier of the zone",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="The title of the zone",
 *         example="North Zone"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         nullable=true,
 *         description="The description of the zone",
 *         example="Northern region of the city"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="boolean",
 *         description="The status of the zone (true for active, false for inactive)",
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
 *         description="The timestamp when the zone was created",
 *         example="2024-01-01T00:00:00.000000Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="The timestamp when the zone was last updated",
 *         example="2024-01-01T00:00:00.000000Z"
 *     ),
 *     @OA\Property(
 *         property="deleted_at",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         description="The timestamp when the zone was soft deleted",
 *         example=null
 *     )
 * )
 */
class ZoneSchema
{
    // This class is used only for OpenAPI documentation
} 