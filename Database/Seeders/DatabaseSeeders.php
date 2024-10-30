<?php

namespace WMIP2C\Database\Seeders;

use WMIP2C\Database\Seeders\Components\AbstractDatabaseSeeders;

/**
 * @method static DatabaseSeeders instance
 */
final class DatabaseSeeders extends AbstractDatabaseSeeders
{
    protected function seeders(): array
    {
        return [
            TotalStatisticsSeeder::class,
            OperatorsSeeder::class,
            FieldsSeeder::class,
            FieldsValuesSeeder::class,
            FieldsOperatorsSeeder::class,
        ];
    }
}