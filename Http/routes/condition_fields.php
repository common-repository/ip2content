<?php

declare (strict_types = 1);

use WMIP2C\Common\Services\Routes\RestRouteService;
use WMIP2C\Http\Controllers\Api\ConditionFieldsApiController;

$conditionFieldsApiController = new ConditionFieldsApiController();

add_action('rest_api_init', function () use ($conditionFieldsApiController) {
    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/fields/list', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => [$conditionFieldsApiController, 'getList'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$conditionFieldsApiController, 'canManageOptions'],
    ]);

    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/fields/(?P<id>[\d]+)/operators', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => [$conditionFieldsApiController, 'getFieldOperatorsList'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$conditionFieldsApiController, 'canManageOptions'],
    ]);

    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/fields/(?P<id>[\d]+)/values', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => [$conditionFieldsApiController, 'getFieldValuesList'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$conditionFieldsApiController, 'canManageOptions'],
    ]);
});
