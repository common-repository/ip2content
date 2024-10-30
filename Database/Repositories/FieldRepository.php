<?php

namespace WMIP2C\Database\Repositories;

use stdClass;
use WMIP2C\Database\Migrations\CreateFieldsTableMigration;
use WMIP2C\Database\Models\Field;

final class FieldRepository
{
    private string $tableName;

    public function __construct()
    {
        $this->tableName = CreateFieldsTableMigration::getTableName();
    }

    public function getById(int $id): ?Field
    {
        global $wpdb;

        $rows = $wpdb->get_results("SELECT * FROM {$this->tableName} WHERE `id` = {$id}");

        if (!isset($rows[0])) {
            return null;
        }

        return $this->mapToEntity($rows[0]);
    }

    /**
     * @return Field[]
     */
    public function getAll(): array
    {
        global $wpdb;

        $rows = $wpdb->get_results("SELECT * FROM {$this->tableName}");

        return $this->mapToEntities($rows);
    }

    private function mapToEntities(array $rows): array
    {
        $entities = [];

        foreach ($rows as $row) {
            $entities[] = $this->mapToEntity($row);
        }

        return $entities;
    }

    private function mapToEntity(stdClass $row): Field
    {
        return new Field(
            (int) $row->id,
            $row->alias,
            $row->source,
            (int) $row->default_operator_id,
        );
    }
}