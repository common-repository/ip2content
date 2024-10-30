<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Controllers\Api;

use WMIP2C\Http\Services\HttpResponse;
use WMIP2C\Http\Services\Statistics\PageViewsStatistics;
use WP_REST_Request;
use WP_REST_Response;

final class ViewsStatisticsApiController
{
    private PageViewsStatistics $pageViewsStatistics;

    public function __construct()
    {
        $this->pageViewsStatistics = new PageViewsStatistics();
    }

    public function updateViewsStatistics(WP_REST_Request $request): WP_REST_Response
    {
        $attributes = $request->get_attributes();
        $userId = (int) $attributes['user_id'];

        if ($userId === 0) {
            $this->pageViewsStatistics->updateViewsStatistics();
        }

        return HttpResponse::successful();
    }

    public function canManageOptions(WP_REST_Request $request): bool
    {
        $attributes = $request->get_attributes();

        return isset($attributes['user_id']) && user_can((int) $attributes['user_id'], 'manage_options');
    }
}