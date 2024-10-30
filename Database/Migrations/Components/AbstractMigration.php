<?php

namespace WMIP2C\Database\Migrations\Components;

abstract class AbstractMigration
{
    protected const PLAIN_TABLE_NAME = self::class;

    public static function getTableName(): string
    {
        global $wpdb;

        $pluginPrefix = WMIP2C_PLUGIN_PREFIX;
        $table = static::PLAIN_TABLE_NAME;

        return "{$wpdb->prefix}{$pluginPrefix}_{$table}";
    }

    abstract public function up(): void;

    public function down(): void
    {
        global $wpdb;

        $tableName = static::getTableName();

        $wpdb->query("DROP TABLE IF EXISTS {$tableName}");
    }
}