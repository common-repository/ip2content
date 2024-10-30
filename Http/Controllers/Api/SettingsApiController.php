<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Controllers\Api;

use WMIP2C\Common\Enums\WordpressOptions;
use WMIP2C\Http\Services\CacheService;
use WMIP2C\Http\Services\HttpResponse;
use WP_REST_Request;
use WP_REST_Response;

final class SettingsApiController extends UserAwareController
{
    private const DEFAULT_LANGUAGE = 'en';
    private $cacheService;


    public function __construct()
    {
        $this->cacheService = new CacheService();
    }

    public function index(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        $settings = $this->cacheService->get('settings');
        if ($settings !== null) {
            return HttpResponse::successful(json_decode($settings, true));
        }

        $data = [
            'ip2c' => (bool) get_option(WordpressOptions::IP2C_ACTIVE),
            'tracking' => (bool) get_option(WordpressOptions::TRACKING_ACTIVE),
            'utm_tracking' => (bool) get_option(WordpressOptions::UTM_TRACKING_ACTIVE),
            'company_detection' => (bool) get_option(WordpressOptions::COMPANY_DETECTION_ACTIVE),
            'cache' => (bool) get_option(WordpressOptions::CACHE_ACTIVE),
            'language' => get_option(WordpressOptions::LANGUAGE, self::DEFAULT_LANGUAGE),
        ];

        $this->cacheService->set('settings', json_encode($data));


        return HttpResponse::successful($data);
    }

    public function store(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        update_option(WordpressOptions::IP2C_ACTIVE, (bool) $request->get_param('ip2c'));
        update_option(WordpressOptions::TRACKING_ACTIVE, (bool) $request->get_param('tracking'));
        update_option(WordpressOptions::UTM_TRACKING_ACTIVE, (bool) $request->get_param('utm_tracking'));
        update_option(WordpressOptions::COMPANY_DETECTION_ACTIVE, (bool) $request->get_param('company_detection'));
        update_option(WordpressOptions::CACHE_ACTIVE, (bool) $request->get_param('cache'));
        update_option(WordpressOptions::LANGUAGE, $request->get_param('language'));

        $this->cacheService->set('settings', json_encode([
            'ip2c' => (bool) $request->get_param('ip2c'),
            'tracking' => (bool) $request->get_param('tracking'),
            'utm_tracking' => (bool) $request->get_param('utm_tracking'),
            'company_detection' => (bool) $request->get_param('company_detection'),
            'cache' => (bool) $request->get_param('cache'),
            'language' => $request->get_param('language'),
        ]));

        return HttpResponse::successful($request->get_params());
    }

    public function clearCache(): WP_REST_Response
    {
        $this->cacheService->deleteCache();

        return HttpResponse::successful([
            'message' => 'Cache was deleted successfully',
        ]);
    }
}
