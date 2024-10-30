<?php

namespace WMIP2C\Database\Migrations;

use WMIP2C\Database\Migrations\Components\AbstractMigration;

final class CreateFieldsOperatorsTableMigration extends AbstractMigration
{
    protected const PLAIN_TABLE_NAME = 'fields_operators';

    public function up(): void
    {
        global $wpdb;

        $tableName = self::getTableName();
        $fieldsTable = CreateFieldsTableMigration::getTableName();
        $operatorsTable = CreateOperatorsTableMigration::getTableName();

        $wpdb->query("
            CREATE TABLE `{$tableName}` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `field_id` bigint(20) unsigned NOT NULL,
                `operator_id` bigint(20) unsigned NOT NULL,
                PRIMARY KEY (`id`),
                KEY `{$tableName}_field_id_foreign` (`field_id`),
                KEY `{$tableName}_operator_id_foreign` (`operator_id`),
                CONSTRAINT `{$tableName}_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `{$fieldsTable}` (`id`),
                CONSTRAINT `{$tableName}_operator_id_foreign` FOREIGN KEY (`operator_id`) REFERENCES `{$operatorsTable}` (`id`)
            )
        ");
    }
}