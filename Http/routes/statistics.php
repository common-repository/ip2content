<?php

declare (strict_types = 1);

use WMIP2C\Common\Services\Routes\RestRouteService;
use WMIP2C\Http\Controllers\Api\StatisticsApiController;
use WMIP2C\Http\Controllers\Api\ViewsStatisticsApiController;

$statisticsApiController = new StatisticsApiController();
$viewsStatisticsApiController = new ViewsStatisticsApiController();

add_action('rest_api_init', function () use ($statisticsApiController, $viewsStatisticsApiController) {
    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/statistics/results', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => [$statisticsApiController, 'getResults'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$statisticsApiController, 'canManageOptions'],
    ]);

    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/statistics/latest-detected-companies', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => [$statisticsApiController, 'getLatestDetectedCompanies'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$statisticsApiController, 'canManageOptions'],
    ]);

    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/statistics/top-conditions', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => [$statisticsApiController, 'getTopConditions'],
        'user_id' => get_current_user_id(),
        'permission_callback' => [$statisticsApiController, 'canManageOptions'],
    ]);

    register_rest_route(RestRouteService::PLUGIN_ROUTE_NAMESPACE, '/statistics/total-views', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => [$viewsStatisticsApiController, 'updateViewsStatistics'],
        'user_id' => get_current_user_id(),
        'permission_callback' => '__return_true',
    ]);
});