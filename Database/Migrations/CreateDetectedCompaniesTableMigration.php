<?php

declare (strict_types = 1);

namespace WMIP2C\Database\Migrations;

use WMIP2C\Database\Migrations\Components\AbstractMigration;

final class CreateDetectedCompaniesTableMigration extends AbstractMigration
{
    protected const PLAIN_TABLE_NAME = 'detected_companies';

    public function up(): void
    {
        global $wpdb;

        $tableName = self::getTableName();

        $wpdb->query("
            CREATE TABLE `{$tableName}` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `views` bigint(20) unsigned NOT NULL DEFAULT '0',
                `branch` varchar(255) NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            )
        ");
    }
}