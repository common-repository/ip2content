<?php

namespace WMIP2C\Database\Repositories;

use stdClass;
use WMIP2C\Database\Migrations\CreateConditionCombinationsTableMigration;
use WMIP2C\Database\Models\ConditionCombination;

final class ConditionCombinationRepository
{
    private string $tableName;

    public function __construct()
    {
        $this->tableName = CreateConditionCombinationsTableMigration::getTableName();
    }

    public function store(array $attributes): ConditionCombination
    {
        $validAttributes = $this->normalizeAttributes($attributes);

        global $wpdb;

        $wpdb->insert($this->tableName, $validAttributes);

        return new ConditionCombination(
            $wpdb->insert_id,
            $attributes['condition_id'] ?? '',
            (bool) $attributes['operator_id'],
            (int) $attributes['content_id'],
            $attributes['value'],
            (int) $attributes['order']
        );
    }

    /**
     * @return ConditionCombination[]
     */
    public function storeMany(array $combinationsAttributes): array
    {
        $combinations = [];

        foreach ($combinationsAttributes as $combinationAttributes) {
            $combinations[] = $this->store($combinationAttributes);
        }

        return $combinations;
    }

    /**
     * @return ConditionCombination[]
     */
    public function getByConditionId(int $conditionId): array
    {
        global $wpdb;

        $rows = $wpdb->get_results("
            SELECT * FROM {$this->tableName} WHERE `condition_id` = {$conditionId} ORDER BY `order`
        ");

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

    public function updateById(int $id, array $attributes): void
    {
        $validAttributes = $this->normalizeAttributes($attributes);

        global $wpdb;

        $wpdb->update($this->tableName, $validAttributes, ['id' => $id]);
    }

    private function mapToEntity(stdClass $row): ConditionCombination
    {
        return new ConditionCombination(
            (int) $row->id,
            (int) $row->condition_id,
            (int) $row->operator_id,
            (int) $row->content_id,
            $row->value,
            $row->order
        );
    }

    private function normalizeAttributes(array $attributes): array
    {
        $validAttributes = [
            'condition_id' => $attributes['condition_id'],
            'operator_id'  => $attributes['operator_id'] ?: null,
            'content_id'   => $attributes['content_id'] ?: null,
            'value'        => $attributes['value'],
            'order'        => $attributes['order'],
        ];

        return array_filter($validAttributes, static function ($attribute) {
            return $attribute !== null;
        });
    }

    public function deleteByConditionIdWhereIdNotIn(int $conditionId, array $combinationsIds): void
    {
        $deleteQuery = "DELETE FROM {$this->tableName} WHERE `condition_id` = {$conditionId}";

        if (!empty($combinationsIds)) {
            $combinationsIdsSet = implode(',', $combinationsIds);
            $deleteQuery .= " AND id NOT IN ({$combinationsIdsSet})";
        }

        global $wpdb;

        $wpdb->query($deleteQuery);
    }
}
