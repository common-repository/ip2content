<?php

namespace WMIP2C\Database\Migrations;

use WMIP2C\Database\Migrations\Components\AbstractDatabaseMigrations;

/**
 * @method static DatabaseMigrations instance
 */
final class DatabaseMigrations extends AbstractDatabaseMigrations
{
    public function migrations(): array
    {
        return [
            CreateOperatorsTableMigration::class,
            CreateFieldsTableMigration::class,
            CreateFieldsOperatorsTableMigration::class,
            CreateFieldValuesTableMigration::class,
            CreateConditionsTableMigration::class,
            CreateConditionCombinationsTableMigration::class,
            CreateDetectedCompaniesTableMigration::class,
            CreateTotalStatisticsTableMigration::class,
        ];
    }
}