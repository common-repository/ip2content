<?php

namespace WMIP2C\Database\Seeders;

use WMIP2C\Common\Enums\ConditionFieldOperators;
use WMIP2C\Database\Migrations\CreateOperatorsTableMigration;
use WMIP2C\Database\Seeders\Components\AbstractSeeder;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\IndustryIsOneOf;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\IntInRange;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\StringContains;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\StringEndsWith;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\StringIsOneOf;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\StringStartsWith;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\UTMContains;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\UTMEndsWith;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\UTMIsOneOf;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\UTMStartsWith;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\ZipCodeContains;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\ZipCodeEndsWith;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\ZipCodeInRange;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\ZipCodeIsOneOf;
use WMIP2C\Http\Services\ConditionsWorkflow\Operators\ZipCodeStartsWith;

final class OperatorsSeeder extends AbstractSeeder
{
    public function run(): void
    {
        global $wpdb;

        $operators = $this->getDump();
        $tableName = CreateOperatorsTableMigration::getTableName();

        foreach ($operators as $operator) {
            $wpdb->insert($tableName, $operator);
        }
    }

    public function getDump(): array
    {
        return [
            [
                'alias' => ConditionFieldOperators::STRING_EQUAL,
                'name_en' => 'Is',
                'name_de' => 'Ist',
                'class' => StringIsOneOf::class,
            ],
            [
                'alias' => ConditionFieldOperators::STRING_STARTS_WITH,
                'name_en' => 'Starts with',
                'name_de' => 'Beginnt mit',
                'class' => StringStartsWith::class,
            ],
            [
                'alias' => ConditionFieldOperators::STRING_ENDS_WITH,
                'name_en' => 'Ends with',
                'name_de' => 'Endet mit',
                'class' => StringEndsWith::class,
            ],
            [
                'alias' => ConditionFieldOperators::STRING_CONTAINS,
                'name_en' => 'Contains',
                'name_de' => 'Beinhaltet',
                'class' => StringContains::class,
            ],
            [
                'alias' => ConditionFieldOperators::INT_RANGE,
                'name_en' => 'Range',
                'name_de' => 'Im Bereich',
                'class' => IntInRange::class,
            ],
            [
                'alias' => ConditionFieldOperators::ZIPCODE_EQUAL,
                'name_en' => 'Is',
                'name_de' => 'Ist',
                'class' => ZipCodeIsOneOf::class,
            ],
            [
                'alias' => ConditionFieldOperators::ZIPCODE_STARTS_WITH,
                'name_en' => 'Starts with',
                'name_de' => 'Beginnt mit',
                'class' => ZipCodeStartsWith::class,
            ],
            [
                'alias' => ConditionFieldOperators::ZIPCODE_ENDS_WITH,
                'name_en' => 'Ends with',
                'name_de' => 'Endet mit',
                'class' => ZipCodeEndsWith::class,
            ],
            [
                'alias' => ConditionFieldOperators::ZIPCODE_CONTAINS,
                'name_en' => 'Contains',
                'name_de' => 'Beinhaltet',
                'class' => ZipCodeContains::class,
            ],
            [
                'alias' => ConditionFieldOperators::ZIPCODE_RANGE,
                'name_en' => 'Range',
                'name_de' => 'Im Bereich',
                'class' => ZipCodeInRange::class,
            ],
            [
                'alias' => ConditionFieldOperators::UTM_EQUAL,
                'name_en' => 'Is',
                'name_de' => 'Ist',
                'class' => UTMIsOneOf::class,
            ],
            [
                'alias' => ConditionFieldOperators::UTM_STARTS_WITH,
                'name_en' => 'Starts with',
                'name_de' => 'Beginnt mit',
                'class' => UTMStartsWith::class,
            ],
            [
                'alias' => ConditionFieldOperators::UTM_ENDS_WITH,
                'name_en' => 'Ends with',
                'name_de' => 'Endet mit',
                'class' => UTMEndsWith::class,
            ],
            [
                'alias' => ConditionFieldOperators::UTM_CONTAINS,
                'name_en' => 'Contains',
                'name_de' => 'Beinhaltet',
                'class' => UTMContains::class,
            ],
            [
                'alias' => ConditionFieldOperators::INDUSTRY_EQUAL,
                'name_en' => 'Is',
                'name_de' => 'Ist',
                'class' => IndustryIsOneOf::class,
            ],
        ];
    }
}
