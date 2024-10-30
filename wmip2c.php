<?php

/*
Plugin Name: IP2Content
Description: IP2Content
Version: 1.8.0
Author: WiredMinds
Author URI: https://wiredminds.de/
Requires PHP: 7.4
Requires at least: 6.1
 */

if (false === PHP_VERSION_ID >= 70400) {
    trigger_error('The WiredMinds IP2Content WordPress plugin requires PHP 7.4 or greater.', E_USER_ERROR);
}

use WMIP2C\Common\PluginEnv;
use WMIP2C\Common\PluginHooks;
use WMIP2C\Common\Services\PluginUrls;

require 'vendor/autoload.php';

const WMIP2C_PLUGIN_PREFIX = 'wmip2c';
const WMIP2C_ROOT_DIR = __DIR__ . '/';

PluginEnv::setPluginData(__FILE__);
PluginUrls::instance()->setRootUrl(__FILE__);

require_once WMIP2C_ROOT_DIR . 'Common/plugin_setup.php';

$pluginHooks = PluginHooks::instance();

register_activation_hook(__FILE__, [$pluginHooks, 'activationHook']);
register_deactivation_hook(__FILE__, [$pluginHooks, 'deactivationHook']);
register_uninstall_hook(__FILE__, [PluginHooks::class, 'uninstallHook']);