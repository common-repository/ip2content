<?php

namespace WMIP2C\Database\Migrations;

use WMIP2C\Database\Migrations\Components\AbstractMigration;

final class CreateOperatorsTableMigration extends AbstractMigration
{
    protected const PLAIN_TABLE_NAME = 'operators';

    public function up(): void
    {
        global $wpdb;

        $tableName = self::getTableName();

        $wpdb->query("
            CREATE TABLE `{$tableName}` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `alias` varchar(255) NOT NULL,
                `name_en` varchar(255) NOT NULL,
                `name_de` varchar(255) NOT NULL,
                `class` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
            )
        ");
    }
}