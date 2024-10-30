<?php

namespace WMIP2C\Database\Repositories;

use stdClass;
use WMIP2C\Database\Migrations\CreateConditionsTableMigration;
use WMIP2C\Database\Migrations\CreateFieldsTableMigration;
use WMIP2C\Database\Models\Condition;

final class ConditionRepository
{
    private string $tableName;

    public function __construct()
    {
        $this->tableName = CreateConditionsTableMigration::getTableName();
    }

    public function updateById(int $id, array $attributes): void
    {
        $validAttributes = $this->normalizeAttributes($attributes);

        global $wpdb;

        $wpdb->update($this->tableName, $validAttributes, ['id' => $id]);
    }

    public function deleteById(int $id): void
    {
        global $wpdb;

        $wpdb->delete($this->tableName, ['id' => $id]);
    }

    public function store(array $attributes): Condition
    {
        $attributes['created_at'] = date('Y-m-d H:i:s');
        $validAttributes = $this->normalizeAttributes($attributes);

        global $wpdb;

        $wpdb->insert($this->tableName, $validAttributes);

        return new Condition(
            $wpdb->insert_id,
            $attributes['label'] ?? '',
            (bool) $attributes['active'],
            (int) $attributes['field_id'],
            (int) $attributes['default_content_id'],
            (int) ($attributes['hits'] ?? 0),
            $attributes['created_at'] ?? '',
            $attributes['updated_at'] ?? '',
        );
    }

    public function getById(int $id): ?Condition
    {
        global $wpdb;

        $rows = $wpdb->get_results("SELECT * FROM {$this->tableName} WHERE `id` = {$id}");

        if (!isset($rows[0])) {
            return null;
        }

        return $this->mapToEntity($rows[0]);
    }

    public function getTenOrderedByHitsDescWithFieldsAliases(): array
    {
        global $wpdb;

        $fieldsTable = CreateFieldsTableMigration::getTableName();

        return $wpdb->get_results("
            SELECT
                {$this->tableName}.*,
                {$fieldsTable}.alias AS `field_alias`
            FROM
                {$this->tableName}
                JOIN {$fieldsTable} ON {$this->tableName}.field_id = {$fieldsTable}.id
            ORDER BY `hits` DESC
            LIMIT 10
        ");
    }

    public function getTotalCount(): int
    {
        global $wpdb;

        $rows = $wpdb->get_results("SELECT COUNT(`id`) AS `count` FROM {$this->tableName}");

        return (int) $rows[0]->count;
    }

    public function getAllWithFieldsWithOffsetAndLimit(int $limit, int $offset): array
    {
        global $wpdb;

        $fieldsTable = CreateFieldsTableMigration::getTableName();

        return $wpdb->get_results("
            SELECT
                {$this->tableName}.*,
                {$fieldsTable}.alias AS `field_alias`
            FROM
                {$this->tableName}
                JOIN {$fieldsTable} ON {$this->tableName}.field_id = {$fieldsTable}.id
            ORDER BY {$this->tableName}.created_at DESC
            LIMIT {$offset}, {$limit}
        ");
    }

    public function updateStatusById(int $id, bool $active): void
    {
        global $wpdb;

        $wpdb->update($this->tableName, ['active' => (int) $active], ['id' => $id]);
    }

    private function mapToEntity(stdClass $row): Condition
    {
        return new Condition(
            (int) $row->id,
            $row->label,
            (bool) $row->active,
            (int) $row->field_id,
            (int) $row->default_content_id,
            (int) $row->hits,
            $row->created_at,
            $row->updated_at
        );
    }

    private function normalizeAttributes(array $attributes): array
    {
        $validAttributes = [
            'label' => $attributes['label'] ?? null,
            'active' => $attributes['active'] ?? null,
            'field_id' => $attributes['field_id'] ?? null,
            'default_content_id' => $attributes['default_content_id'] ?? null,
            'hits' => $attributes['hits'] ?? 0,
            'created_at' => $attributes['created_at'] ?? '',
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        return array_filter($validAttributes, static function ($attribute) {
            return $attribute !== null;
        });
    }
}