<?php

use WMIP2C\Common\Enums\EnqueuedScriptAliases;
use WMIP2C\Common\Enums\WordpressActions;
use WMIP2C\Common\PluginEnv;
use WMIP2C\Common\Services\PluginUrls;
use WMIP2C\Common\Services\Routes\RestRouteService;

add_action(WordpressActions::WP_LOADED, function () {
    wp_register_script(EnqueuedScriptAliases::BRIDGE, PluginUrls::getScriptsUrl() . 'data_bridge.js');
    wp_localize_script(EnqueuedScriptAliases::BRIDGE, 'dataBridge', [
        'plugin_api_namespace' => RestRouteService::getPrefix(),
        'wp_api_namespace' => RestRouteService::getPrefix(true),
        'plugin_version' => PluginEnv::$version,
    ]);
}, 9);

add_action(WordpressActions::ENQUE_SCRIPTS, function () {
    wp_enqueue_script(EnqueuedScriptAliases::BRIDGE);
}, 9);

add_action(WordpressActions::ADMIN_ENQUE_SCRIPTS, function () {
    wp_enqueue_script(EnqueuedScriptAliases::BRIDGE);
}, 9);

include_once WMIP2C_ROOT_DIR . 'Http/init.php';
include_once WMIP2C_ROOT_DIR . 'Admin/init.php';
include_once WMIP2C_ROOT_DIR . 'Public/init.php';

require_once __DIR__ . '/CustomPostTypes/register.php';
require_once WMIP2C_ROOT_DIR . 'extensions/leadlab/init.php';