<?php

use WMIP2C\Common\Services\Routes\RestRouteService;
use WMIP2C\Http\Controllers\Api\ConditionsApiController;

$conditionsApiController = new ConditionsApiController();

add_action('rest_api_init', function () use ($conditionsApiController) {
    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/conditions', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => [$conditionsApiController, 'index'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$conditionsApiController, 'canManageOptions'],
    ]);

    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/conditions/(?P<id>[\d]+)', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => [$conditionsApiController, 'edit'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$conditionsApiController, 'canManageOptions'],
    ]);

    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/conditions', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => [$conditionsApiController, 'store'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$conditionsApiController, 'canManageOptions'],
    ]);

    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/conditions/(?P<id>[\d]+)/update', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => [$conditionsApiController, 'update'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$conditionsApiController, 'canManageOptions'],
    ]);

    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/conditions/(?P<id>[\d]+)/status', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => [$conditionsApiController, 'updateStatus'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$conditionsApiController, 'canManageOptions'],
    ]);

    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/conditions/delete', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => [$conditionsApiController, 'delete'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$conditionsApiController, 'canManageOptions'],
    ]);
});