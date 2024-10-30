<?php

use WMIP2C\Common\Services\Routes\RestRouteService;
use WMIP2C\Http\Controllers\Api\ConditionApplyingApiController;

$conditionsApplyingApiController = new ConditionApplyingApiController();

add_action('rest_api_init', function () use ($conditionsApplyingApiController) {
    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/conditions/check', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => [$conditionsApplyingApiController, 'applyConditions'],
        'user_id' => get_current_user_id(),
        'permission_callback' => '__return_true',
    ]);
});