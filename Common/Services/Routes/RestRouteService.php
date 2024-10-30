<?php

namespace WMIP2C\Common\Services\Routes;

use WMIP2C\Common\Enums\WordpressOptions;

final class RestRouteService
{
    private const REST_ROUTE_PARAM_NAME = '?rest_route=';
    private const BEAUTIFUL_PERMALINK_NAMESPACE_PREFIX = '/wp-json/';
    private const WP_ROUTE_NAMESPACE = '/wp/v2/';

    public const PLUGIN_ROUTE_NAMESPACE = 'wp/v2';

    public static function getPrefix(bool $wpPrefix = false): string
    {
        $prefix = self::getNamespacePrefixByPermalink();

        $routeNamespace = $wpPrefix ? self::WP_ROUTE_NAMESPACE : '//' . self::PLUGIN_ROUTE_NAMESPACE . '//';

        return str_replace('//', '/', $prefix . $routeNamespace);
    }

    private static function getNamespacePrefixByPermalink(): string
    {
        return self::is_permalink_plain()
        ? self::REST_ROUTE_PARAM_NAME
        : self::BEAUTIFUL_PERMALINK_NAMESPACE_PREFIX;
    }

    public static function is_permalink_plain(): bool
    {
        return !get_option(WordpressOptions::PERMALINK_STRUCTURE);
    }
}