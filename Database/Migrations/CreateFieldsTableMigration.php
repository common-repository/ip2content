<?php

namespace WMIP2C\Database\Migrations;

use WMIP2C\Database\Migrations\Components\AbstractMigration;

final class CreateFieldsTableMigration extends AbstractMigration
{
    protected const PLAIN_TABLE_NAME = 'fields';

    public function up(): void
    {
        global $wpdb;

        $tableName = self::getTableName();
        $operatorsTable = CreateOperatorsTableMigration::getTableName();

        $wpdb->query("
            CREATE TABLE `{$tableName}` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `alias` varchar(255) NOT NULL,
                `source` varchar(255) NOT NULL,
                `default_operator_id` bigint(20) unsigned NOT NULL,
                PRIMARY KEY (`id`),
                KEY `{$tableName}_default_operator_id_foreign` (`default_operator_id`),
                CONSTRAINT `{$tableName}_default_operator_id_foreign` FOREIGN KEY (`default_operator_id`) REFERENCES `{$operatorsTable}` (`id`)
            )
        ");
    }
}