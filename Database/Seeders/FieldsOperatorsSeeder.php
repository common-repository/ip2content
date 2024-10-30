<?php

namespace WMIP2C\Database\Seeders;

use WMIP2C\Common\Enums\ConditionFieldOperators;
use WMIP2C\Common\Enums\ConditionFields;
use WMIP2C\Database\Migrations\CreateFieldsOperatorsTableMigration;
use WMIP2C\Database\Migrations\CreateFieldsTableMigration;
use WMIP2C\Database\Migrations\CreateOperatorsTableMigration;
use WMIP2C\Database\Models\Field;
use WMIP2C\Database\Models\Operator;
use WMIP2C\Database\Seeders\Components\AbstractSeeder;

final class FieldsOperatorsSeeder extends AbstractSeeder
{
    /**
     * @var Field[]
     */
    protected array $fieldsByAlias;
    /**
     * @var Operator[]
     */
    protected array $operatorsByAlias;

    protected array $rawRelation = [
        ConditionFields::INDUSTRY => [],
        ConditionFields::COMPANY_NAME => [
            ConditionFieldOperators::STRING_EQUAL,
            ConditionFieldOperators::STRING_STARTS_WITH,
            ConditionFieldOperators::STRING_ENDS_WITH,
            ConditionFieldOperators::STRING_CONTAINS,
        ],
        ConditionFields::ZIP_CODE => [
            ConditionFieldOperators::ZIPCODE_EQUAL,
            ConditionFieldOperators::ZIPCODE_STARTS_WITH,
            ConditionFieldOperators::ZIPCODE_ENDS_WITH,
            ConditionFieldOperators::ZIPCODE_CONTAINS,
            ConditionFieldOperators::ZIPCODE_RANGE,
        ],
        ConditionFields::DOMAIN => [
            ConditionFieldOperators::STRING_EQUAL,
            ConditionFieldOperators::STRING_STARTS_WITH,
            ConditionFieldOperators::STRING_ENDS_WITH,
            ConditionFieldOperators::STRING_CONTAINS,
        ],
        ConditionFields::UTM => [
            ConditionFieldOperators::UTM_EQUAL,
            ConditionFieldOperators::UTM_STARTS_WITH,
            ConditionFieldOperators::UTM_ENDS_WITH,
            ConditionFieldOperators::UTM_CONTAINS,
        ],
        ConditionFields::COMPANY_SIZE => [],
        ConditionFields::COUNTRY => [],
        ConditionFields::REVENUE_CLASS => [],
    ];

    public function __construct()
    {
        global $wpdb;

        $fieldsTable = CreateFieldsTableMigration::getTableName();
        $rows = $wpdb->get_results("SELECT * FROM {$fieldsTable}");

        $this->fieldsByAlias = [];

        foreach ($rows as $row) {
            $this->fieldsByAlias[$row->alias] = new Field(
                (int) $row->id,
                $row->alias,
                $row->source,
                (int) $row->default_operator_id,
            );
        }

        $operatorTable = CreateOperatorsTableMigration::getTableName();
        $rows = $wpdb->get_results("SELECT * FROM {$operatorTable}");

        $this->operatorsByAlias = [];

        foreach ($rows as $row) {
            $this->operatorsByAlias[$row->alias] = new Operator(
                (int) $row->id,
                $row->alias,
                $row->name_en,
                $row->name_de,
                $row->class,
            );
        }
    }

    public function run(): void
    {
        global $wpdb;

        $fieldsOperatorsTable = CreateFieldsOperatorsTableMigration::getTableName();

        $fieldsOperators = $this->getDump();

        foreach ($fieldsOperators as $fieldOperator) {
            $wpdb->insert($fieldsOperatorsTable, $fieldOperator);
        }
    }

    public function getDump(): array
    {
        $dump = [];

        foreach ($this->rawRelation as $fieldAlias => $operators) {
            $fieldId = $this->fieldsByAlias[$fieldAlias]->getId();

            foreach ($operators as $operator) {
                $dump[] = [
                    'operator_id' => $this->operatorsByAlias[$operator]->getId(),
                    'field_id' => $fieldId,
                ];
            }
        }

        return $dump;
    }
}