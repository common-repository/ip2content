<?php

declare (strict_types = 1);

use WMIP2C\Common\Services\Routes\RestRouteService;
use WMIP2C\Http\Controllers\Api\LicensesApiController;

$licensesApiController = new LicensesApiController();

add_action('rest_api_init', function () use ($licensesApiController) {
    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/licenses', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => [$licensesApiController, 'index'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$licensesApiController, 'canManageOptions'],
    ]);

    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/licenses', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => [$licensesApiController, 'store'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$licensesApiController, 'canManageOptions'],
    ]);
});