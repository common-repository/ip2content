<?php

use WMIP2C\Common\Enums\EnqueuedScriptAliases;
use WMIP2C\Common\Enums\WordpressActions;
use WMIP2C\Common\Services\PluginUrls;

add_action(WordpressActions::WP_LOADED, function () {
    wp_register_script(
        EnqueuedScriptAliases::SHORTCODE,
        PluginUrls::getScriptsUrl('shortcode.js'),
        null,
        null,
        true
    );

    wp_register_script(
        EnqueuedScriptAliases::TOTAL_VIEWS_COUNTER,
        PluginUrls::getScriptsUrl('total_views_counter.js'),
        null,
        null,
        true
    );
}, 9);

add_action(WordpressActions::ENQUE_SCRIPTS, function () {
    wp_enqueue_script(EnqueuedScriptAliases::SHORTCODE);
    wp_enqueue_script(EnqueuedScriptAliases::TOTAL_VIEWS_COUNTER);
});