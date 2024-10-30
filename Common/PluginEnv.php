<?php

namespace WMIP2C\Common;

if (!function_exists('get_plugin_data')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

class PluginEnv
{
    public static string $version = '';

    public static function setPluginData($pluginFilePath): void
    {
        $pluginData = get_plugin_data($pluginFilePath);
        self::$version = $pluginData['Version'];
    }
}