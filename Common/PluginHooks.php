<?php

namespace WMIP2C\Common;

use WMIP2C\Common\Enums\WordpressOptions;
use WMIP2C\Common\Services\SingletonTrait;
use WMIP2C\Database\DatabaseUpdateManager;
use WMIP2C\Database\Migrations\DatabaseMigrations;
use WMIP2C\Database\Seeders\DatabaseSeeders;

/**
 * @method static PluginHooks instance
 */
final class PluginHooks
{
    use SingletonTrait;

    public function activationHook(): void
    {
        $databaseUpdateManager = new DatabaseUpdateManager();

        if (!$databaseUpdateManager->needsUpdate()) {
            return;
        }

        update_option(WordpressOptions::UTM_TRACKING_ACTIVE, true);

        DatabaseMigrations::instance()->drop();
        DatabaseMigrations::instance()->run();
        DatabaseSeeders::instance()->run();
    }

    public function deactivationHook(): void
    {
        flush_rewrite_rules();
    }

    public static function uninstallHook(): void
    {
        flush_rewrite_rules();
        DatabaseMigrations::instance()->drop();
    }
}