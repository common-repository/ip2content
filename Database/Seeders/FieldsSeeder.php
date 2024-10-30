<?php

namespace WMIP2C\Database\Seeders;

use WMIP2C\Common\Enums\ConditionFieldOperators;
use WMIP2C\Common\Enums\ConditionFields;
use WMIP2C\Common\Enums\ConditionFieldSources;
use WMIP2C\Database\Migrations\CreateFieldsTableMigration;
use WMIP2C\Database\Migrations\CreateOperatorsTableMigration;
use WMIP2C\Database\Seeders\Components\AbstractSeeder;

final class FieldsSeeder extends AbstractSeeder
{
    public function run(): void
    {
        global $wpdb;

        $operatorsTable = CreateOperatorsTableMigration::getTableName();
        $fieldsTable = CreateFieldsTableMigration::getTableName();

        $fieldsDump = $this->getDump();

        foreach ($fieldsDump as $field) {
            $defaultOperator = $wpdb->get_row(
                "SELECT * FROM {$operatorsTable} WHERE `alias` = '{$field['default_operator']}'"
            );

            if (!isset($defaultOperator)) {
                continue;
            }

            $wpdb->insert($fieldsTable, [
                'alias' => $field['alias'],
                'source' => $field['source'],
                'default_operator_id' => $defaultOperator->id,
            ]);
        }
    }

    protected function getDump(): array
    {
        return [
            [
                'alias' => ConditionFields::INDUSTRY,
                'source' => ConditionFieldSources::IP2C,
                'default_operator' => ConditionFieldOperators::INDUSTRY_EQUAL,
            ],
            [
                'alias' => ConditionFields::COMPANY_NAME,
                'source' => ConditionFieldSources::IP2C,
                'default_operator' => ConditionFieldOperators::STRING_EQUAL,
            ],
            [
                'alias' => ConditionFields::COMPANY_SIZE,
                'source' => ConditionFieldSources::IP2C,
                'default_operator' => ConditionFieldOperators::STRING_EQUAL,
            ],
            [
                'alias' => ConditionFields::COUNTRY,
                'source' => ConditionFieldSources::IP2C,
                'default_operator' => ConditionFieldOperators::STRING_EQUAL,
            ],
            [
                'alias' => ConditionFields::ZIP_CODE,
                'source' => ConditionFieldSources::IP2C,
                'default_operator' => ConditionFieldOperators::ZIPCODE_EQUAL,
            ],
            [
                'alias' => ConditionFields::DOMAIN,
                'source' => ConditionFieldSources::IP2C,
                'default_operator' => ConditionFieldOperators::STRING_EQUAL,
            ],
            [
                'alias' => ConditionFields::REVENUE_CLASS,
                'source' => ConditionFieldSources::IP2C,
                'default_operator' => ConditionFieldOperators::STRING_EQUAL,
            ],
            [
                'alias' => ConditionFields::UTM,
                'source' => ConditionFieldSources::UTM,
                'default_operator' => ConditionFieldOperators::UTM_EQUAL,
            ],
        ];
    }
}