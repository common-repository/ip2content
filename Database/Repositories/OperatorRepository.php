<?php

namespace WMIP2C\Database\Repositories;

use stdClass;
use WMIP2C\Database\Migrations\CreateFieldsOperatorsTableMigration;
use WMIP2C\Database\Migrations\CreateOperatorsTableMigration;
use WMIP2C\Database\Models\Operator;

final class OperatorRepository
{
    private string $tableName;

    public function __construct()
    {
        $this->tableName = CreateOperatorsTableMigration::getTableName();
    }

    public function getById(int $id): ?Operator
    {
        global $wpdb;

        $rows = $wpdb->get_results("SELECT * FROM {$this->tableName} WHERE `id` = {$id}");

        if (!isset($rows[0])) {
            return null;
        }

        return $this->mapToEntity($rows[0]);
    }

    public function getByFieldId(int $fieldId): array
    {
        global $wpdb;

        $fieldsOperatorsTable = CreateFieldsOperatorsTableMigration::getTableName();

        $rows = $wpdb->get_results("
            SELECT
                {$this->tableName}.*
            FROM
                {$this->tableName}
                JOIN {$fieldsOperatorsTable} ON {$this->tableName}.id = {$fieldsOperatorsTable}.operator_id
            WHERE
                {$fieldsOperatorsTable}.field_id = {$fieldId}
        ");

        return $this->mapToEntities($rows);
    }

    /**
     * @return Operator[]
     */
    private function mapToEntities(array $rows): array
    {
        $entities = [];

        foreach ($rows as $row) {
            $entities[] = $this->mapToEntity($row);
        }

        return $entities;
    }

    private function mapToEntity(stdClass $row): Operator
    {
        return new Operator(
            (int) $row->id,
            $row->alias,
            $row->name_en,
            $row->name_de,
            $row->class,
        );
    }
}
