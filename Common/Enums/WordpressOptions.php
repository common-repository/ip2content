<?php

namespace WMIP2C\Common\Enums;

final class WordpressOptions
{
    public const TRACKING_TOKEN = WMIP2C_PLUGIN_PREFIX . '_tracking_token';
    public const IP2C_TOKEN = WMIP2C_PLUGIN_PREFIX . '_ip2c_token';
    public const IP2C_ACTIVE = WMIP2C_PLUGIN_PREFIX . '_ip2c_active';
    public const TRACKING_ACTIVE = WMIP2C_PLUGIN_PREFIX . '_tracking_active';
    public const UTM_TRACKING_ACTIVE = WMIP2C_PLUGIN_PREFIX . '_utm_tracking_active';
    public const COMPANY_DETECTION_ACTIVE = WMIP2C_PLUGIN_PREFIX . '_company_detection_active';
    public const CACHE_ACTIVE = WMIP2C_PLUGIN_PREFIX . '_cache_active';
    public const LANGUAGE = WMIP2C_PLUGIN_PREFIX . '_lang';
    public const PERMALINK_STRUCTURE = 'permalink_structure';
}