<?php

namespace WMIP2C\Admin\Modules\Conditions;

use WMIP2C\Common\CustomPostTypes\Conditions\ConditionsPostType;
use WMIP2C\Common\Enums\EnqueuedScriptAliases;
use WMIP2C\Common\Enums\WordpressActions;
use WMIP2C\Common\Services\PluginUrls;
use WMIP2C\Http\Controllers\Web\ConditionWebController;

final class ConditionsPage
{
    public const PAGE_SLUG = WMIP2C_PLUGIN_PREFIX . '-conditions';

    public function init(): void
    {
        add_action(WordpressActions::WP_LOADED, function () {
            $this->registerAssets();
        }, 9);
        add_action('admin_menu', [$this, 'addConditionsPage']);
    }

    private function registerAssets(): void
    {
        wp_register_style(
            EnqueuedScriptAliases::MATERIAL_DESIGN_ICONS,
            PluginUrls::getStylesUrl('material_design_icons.min.css'),
        );
        wp_register_style(
            EnqueuedScriptAliases::IP_CONDITIONS_CSS,
            PluginUrls::getStylesUrl('ip_conditions.css'),
            [EnqueuedScriptAliases::MATERIAL_DESIGN_ICONS],
        );
        wp_register_script(
            EnqueuedScriptAliases::IP_CONDITIONS_VUE,
            PluginUrls::getScriptsUrl('ip_conditions.js'),
            null,
            null,
            true
        );
    }

    public function addConditionsPage(): void
    {
        add_menu_page(
            'IP2Content',
            'IP2Content',
            'manage_options',
            self::PAGE_SLUG,
            [$this, 'getPageContent'],
            PluginUrls::getImagesUrl('ip_conditions/ip2c_icon.png'),
            100
        );

        $contentsPostTypeName = ConditionsPostType::POST_TYPE_NAME;
        add_submenu_page(
            self::PAGE_SLUG,
            'Conditions Content',
            'Conditions Content',
            'manage_options',
            "edit.php?post_type=$contentsPostTypeName"
        );
    }

    public function getPageContent(): void
    {
        echo ConditionWebController::instance()->index();
    }
}