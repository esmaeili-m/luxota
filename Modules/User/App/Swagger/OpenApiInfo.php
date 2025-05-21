<?php

namespace Modules\User\App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="My Laravel Modular API",
 *     version="1.0.0",
 *     description="This is the Swagger documentation for the Laravel Modular API project."
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Main API Server"
 * )
 */
class OpenApiInfo {}
