<?php

namespace WMIP2C\Database\Migrations;

use WMIP2C\Database\Migrations\Components\AbstractMigration;

final class CreateFieldValuesTableMigration extends AbstractMigration
{
    protected const PLAIN_TABLE_NAME = 'field_values';

    public function up(): void
    {
        global $wpdb;

        $tableName = self::getTableName();
        $fieldsTable = CreateFieldsTableMigration::getTableName();

        $wpdb->query("
            CREATE TABLE `{$tableName}` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `name_en` varchar(255) NOT NULL,
                `name_de` varchar(255) NOT NULL,
                `value` varchar(255) NOT NULL,
                `field_id` bigint(20) unsigned NOT NULL,
                PRIMARY KEY (`id`),
                KEY `{$tableName}_field_id_foreign` (`field_id`),
                CONSTRAINT `{$tableName}_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `{$fieldsTable}` (`id`)
            )
        ");
    }
}