<?php

namespace WMIP2C\Database\Migrations;

use WMIP2C\Database\Migrations\Components\AbstractMigration;

final class CreateConditionsTableMigration extends AbstractMigration
{
    protected const PLAIN_TABLE_NAME = 'conditions';

    public function up(): void
    {
        global $wpdb;

        $tableName = self::getTableName();
        $fieldsTable = CreateFieldsTableMigration::getTableName();

        $wpdb->query("
            CREATE TABLE `{$tableName}` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `label` varchar(255) NOT NULL,
                `active` tinyint(1) NOT NULL DEFAULT 0,
                `field_id` bigint(20) unsigned NOT NULL,
                `default_content_id` bigint(20) unsigned DEFAULT NULL,
                `hits` bigint(20) unsigned NOT NULL DEFAULT 0,
                `created_at` datetime NULL DEFAULT NULL,
                `updated_at` datetime NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `{$tableName}_field_id_foreign` (`field_id`),
                KEY `{$tableName}_hits_index` (`hits`),
                    CONSTRAINT `{$tableName}_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `{$fieldsTable}` (`id`)
            )
        ");
    }
}