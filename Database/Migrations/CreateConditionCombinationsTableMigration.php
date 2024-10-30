<?php

namespace WMIP2C\Database\Migrations;

use WMIP2C\Database\Migrations\Components\AbstractMigration;

final class CreateConditionCombinationsTableMigration extends AbstractMigration
{
    protected const PLAIN_TABLE_NAME = 'condition_combinations';

    public function up(): void
    {
        global $wpdb;

        $tableName = self::getTableName();
        $operatorsTable = CreateOperatorsTableMigration::getTableName();
        $conditionsTable = CreateConditionsTableMigration::getTableName();

        $wpdb->query("
            CREATE TABLE `{$tableName}` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `condition_id` bigint(20) unsigned NOT NULL,
                `value` varchar(255) NOT NULL,
                `content_id` bigint(20) unsigned DEFAULT NULL,
                `operator_id` bigint(20) unsigned NOT NULL,
                `order` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`),
                KEY `{$tableName}_operator_id_foreign` (`operator_id`),
                CONSTRAINT `{$tableName}_operator_id_foreign` FOREIGN KEY (`operator_id`) REFERENCES `{$operatorsTable}` (`id`),
                KEY `{$tableName}_condition_id_foreign` (`condition_id`),
                CONSTRAINT `{$tableName}_condition_id_foreign` FOREIGN KEY (`condition_id`) REFERENCES `{$conditionsTable}` (`id`) ON DELETE CASCADE
            )
        ");
    }
}