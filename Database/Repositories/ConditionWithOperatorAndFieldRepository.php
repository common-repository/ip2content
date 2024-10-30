<?php

namespace WMIP2C\Database\Repositories;

use stdClass;
use WMIP2C\Database\Migrations\CreateConditionsTableMigration;
use WMIP2C\Database\Migrations\CreateFieldsTableMigration;
use WMIP2C\Database\Models\Condition;
use WMIP2C\Database\Models\Field;
use WMIP2C\Http\Data\ConditionFieldAggregate;

final class ConditionWithOperatorAndFieldRepository
{
    private string $tableName;

    public function __construct()
    {
        $this->tableName = CreateConditionsTableMigration::getTableName();
    }

    /**
     * @return ConditionFieldAggregate[]
     */
    public function getActiveByIds(array $ids): array
    {
        global $wpdb;

        $idsSet = implode(',', $ids);
        $fieldsTable = CreateFieldsTableMigration::getTableName();

        $rows = $wpdb->get_results("
            SELECT
                {$this->tableName}.id AS condition_id,
                {$this->tableName}.label AS condition_label,
                {$this->tableName}.active AS condition_active,
                {$this->tableName}.field_id AS condition_field_id,
                {$this->tableName}.default_content_id AS condition_default_content_id,
                {$this->tableName}.hits AS condition_hits,
                {$this->tableName}.created_at AS condition_created_at,
                {$this->tableName}.updated_at AS condition_updated_at,
                {$fieldsTable}.id AS field_id,
                {$fieldsTable}.alias AS field_alias,
                {$fieldsTable}.source AS field_source,
                {$fieldsTable}.default_operator_id AS field_default_operator_id
            FROM
                {$this->tableName}
                JOIN {$fieldsTable} ON {$this->tableName}.field_id = {$fieldsTable}.id
            WHERE
                {$this->tableName}.active = 1 AND
                {$this->tableName}.id IN ({$idsSet})
        ");

        return $this->mapToEntities($rows);
    }

    private function mapToEntities(array $rows): array
    {
        $entities = [];

        foreach ($rows as $row) {
            $conditionOperatorFieldAggregate = $this->mapToEntity($row);
            $conditionId = $conditionOperatorFieldAggregate->getCondition()->getId();

            $entities[$conditionId] = $conditionOperatorFieldAggregate;
        }

        return $entities;
    }

    private function mapToEntity(stdClass $row): ConditionFieldAggregate
    {
        $condition = $this->mapToCondition($row);
        $field = $this->mapToField($row);

        return new ConditionFieldAggregate($condition, $field);
    }

    private function mapToCondition(stdClass $row): Condition
    {
        return new Condition(
            (int) $row->condition_id,
            $row->condition_label,
            (bool) $row->condition_active,
            (int) $row->condition_field_id,
            (int) $row->condition_default_content_id,
            (int) $row->condition_hits,
            $row->condition_created_at,
            $row->condition_updated_at
        );
    }

    public function mapToField(stdClass $row): Field
    {
        return new Field(
            (int) $row->field_id,
            $row->field_alias,
            $row->field_source,
            (int) $row->field_default_operator_id
        );
    }
}
