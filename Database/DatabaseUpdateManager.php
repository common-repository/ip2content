<?php

declare (strict_types = 1);

namespace WMIP2C\Database;

use WMIP2C\Database\Migrations\CreateConditionCombinationsTableMigration;
use WMIP2C\Database\Migrations\CreateConditionsTableMigration;
use WMIP2C\Database\Migrations\CreateDetectedCompaniesTableMigration;
use WMIP2C\Database\Migrations\CreateFieldsOperatorsTableMigration;
use WMIP2C\Database\Migrations\CreateFieldsTableMigration;
use WMIP2C\Database\Migrations\CreateFieldValuesTableMigration;
use WMIP2C\Database\Migrations\CreateTotalStatisticsTableMigration;

final class DatabaseUpdateManager
{
    private array $tables;

    public function __construct()
    {
        $this->tables = [
            CreateConditionCombinationsTableMigration::getTableName(),
            CreateConditionsTableMigration::getTableName(),
            CreateDetectedCompaniesTableMigration::getTableName(),
            CreateFieldsOperatorsTableMigration::getTableName(),
            CreateFieldsTableMigration::getTableName(),
            CreateFieldValuesTableMigration::getTableName(),
            CreateFieldsOperatorsTableMigration::getTableName(),
            CreateTotalStatisticsTableMigration::getTableName(),
        ];
    }

    public function needsUpdate(): bool
    {
        foreach ($this->tables as $table) {
            $tableExists = $this->checkIfTableExists($table);

            if (!$tableExists) {
                return true;
            }
        }

        return false;
    }

    private function checkIfTableExists(string $tableName): bool
    {
        global $wpdb;

        $result = $wpdb->get_var("SHOW TABLES LIKE '{$tableName}';");

        return !empty($result);
    }
}