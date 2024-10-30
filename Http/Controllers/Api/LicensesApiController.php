<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Controllers\Api;

use WMIP2C\Common\Enums\WordpressOptions;
use WMIP2C\Http\Services\HttpResponse;
use WP_REST_Request;
use WP_REST_Response;

final class LicensesApiController extends UserAwareController
{
    public function index(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        return HttpResponse::successful([
            'tracking' => get_option(WordpressOptions::TRACKING_TOKEN, ''),
            'ip2c' => get_option(WordpressOptions::IP2C_TOKEN, ''),
        ]);
    }

    public function store(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        update_option(WordpressOptions::TRACKING_TOKEN, trim($request->get_param('tracking')));
        update_option(WordpressOptions::IP2C_TOKEN, trim($request->get_param('ip2c')));

        return HttpResponse::successful($request->get_params());
    }
}