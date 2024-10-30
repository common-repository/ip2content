<?php

namespace WMIP2C\Http\Services;

use WMIP2C\Common\Services\Singleton;
use WP_REST_Response;

class HttpResponse extends Singleton
{
    public static function successful($data = null): WP_REST_Response
    {
        return new WP_REST_Response($data, 200);
    }

    public static function failed($data = null): WP_REST_Response
    {
        return new WP_REST_Response($data, 400);
    }

    public static function successfulCreated($data = null): WP_REST_Response
    {
        return new WP_REST_Response($data, 201);
    }

    public static function successfulNoContent(): WP_REST_Response
    {
        return new WP_REST_Response(null, 204);
    }

    public static function failedNotFound(): WP_REST_Response
    {
        return new WP_REST_Response(['message' => 'Not Found'], 404);
    }

    public static function failedValidationError(array $errors = []): WP_REST_Response
    {
        return new WP_REST_Response(['message' => 'Invalid input', 'errors' => $errors], 422);
    }
}
