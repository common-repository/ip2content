<?php

declare (strict_types = 1);

namespace WMIP2C\Database\Repositories;

use stdClass;
use WMIP2C\Database\Migrations\CreateFieldValuesTableMigration;
use WMIP2C\Database\Models\FieldValue;

final class FieldValueRepository
{
    private string $tableName;

    public function __construct()
    {
        $this->tableName = CreateFieldValuesTableMigration::getTableName();
    }

    /**
     * @return FieldValue[]
     */
    public function getByFieldId(int $fieldId): array
    {
        global $wpdb;

        $rows = $wpdb->get_results("SELECT * FROM {$this->tableName} WHERE `field_id` = {$fieldId}");

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

    private function mapToEntity(stdClass $row): FieldValue
    {
        return new FieldValue(
            (int) $row->id,
            $row->value,
            $row->name_en,
            $row->name_de,
            (int) $row->field_id,
        );
    }
}