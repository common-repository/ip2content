<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Controllers\Api;

use WP_REST_Request;

abstract class UserAwareController
{
    protected function isInternalUser(WP_REST_Request $request): bool
    {
        $attributes = $request->get_attributes();

        return isset($attributes['user_id']) && (int) $attributes['user_id'] !== 0;
    }

    public function canManageOptions(WP_REST_Request $request): bool
    {
        $attributes = $request->get_attributes();

        return isset($attributes['user_id']) && user_can((int) $attributes['user_id'], 'manage_options');
    }
}