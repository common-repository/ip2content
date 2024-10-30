<?php

declare (strict_types = 1);

use WMIP2C\Common\Services\Routes\RestRouteService;
use WMIP2C\Http\Controllers\Api\SettingsApiController;

$settingsApiController = new SettingsApiController();

add_action('rest_api_init', function () use ($settingsApiController) {
    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/settings', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => [$settingsApiController, 'index'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$settingsApiController, 'canManageOptions'],
    ]);

    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/settings', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => [$settingsApiController, 'store'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$settingsApiController, 'canManageOptions'],
    ]);

    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/settings/clear-cache', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => [$settingsApiController, 'clearCache'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$settingsApiController, 'canManageOptions'],
    ]);
});
