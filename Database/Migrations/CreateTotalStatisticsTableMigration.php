<?php

declare (strict_types = 1);

namespace WMIP2C\Database\Migrations;

use WMIP2C\Database\Migrations\Components\AbstractMigration;

final class CreateTotalStatisticsTableMigration extends AbstractMigration
{
    protected const PLAIN_TABLE_NAME = 'total_statistics';

    public function up(): void
    {
        global $wpdb;

        $tableName = self::getTableName();

        $wpdb->query("
            CREATE TABLE `{$tableName}` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `total_views` bigint(20) unsigned NOT NULL DEFAULT '0',
                `total_hits` bigint(20) unsigned NOT NULL DEFAULT '0',
                `ip2c_hits` bigint(20) unsigned NOT NULL DEFAULT '0',
                `companies_detected` bigint(20) unsigned NOT NULL DEFAULT '0',
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            )
        ");
    }
}