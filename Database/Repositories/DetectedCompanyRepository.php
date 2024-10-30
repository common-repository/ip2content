<?php

namespace WMIP2C\Database\Repositories;

use stdClass;
use WMIP2C\Database\Migrations\CreateDetectedCompaniesTableMigration;
use WMIP2C\Database\Models\DetectedCompany;

final class DetectedCompanyRepository
{
    private string $tableName;

    public function __construct()
    {
        $this->tableName = CreateDetectedCompaniesTableMigration::getTableName();
    }

    public function updateById(int $id, array $attributes): void
    {
        global $wpdb;

        $wpdb->update($this->tableName, $attributes, ['id' => $id]);
    }

    public function store(array $attributes): DetectedCompany
    {
        global $wpdb;

        $wpdb->insert($this->tableName, $attributes);

        return new DetectedCompany(
            $wpdb->insert_id,
            $attributes['name'] ?? '',
            $attributes['views'] ?? 0,
            $attributes['branch'] ?? ''
        );
    }

    public function deleteWhereIdIn(array $ids): void
    {
        global $wpdb;

        $idsSet = implode(',', $ids);

        $wpdb->query("DELETE FROM {$this->tableName} WHERE `id` IN ({$idsSet})");
    }

    public function getFirstByNameAndIndustry(string $name, string $branch): ?DetectedCompany
    {
        global $wpdb;

        $rows = $wpdb->get_results("
            SELECT * FROM {$this->tableName} WHERE `name` = '{$name}' AND `branch` = '{$branch}'
        ");

        if (!isset($rows[0])) {
            return null;
        }

        return $this->mapToEntity($rows[0]);
    }

    /**
     * @return DetectedCompany[]
     */
    public function getFirstTenOrderedByCreatedAtDesc(): array
    {
        global $wpdb;

        $rows = $wpdb->get_results("SELECT * FROM {$this->tableName} ORDER BY `created_at` DESC LIMIT 10");

        return $this->mapToEntities($rows);
    }

    /**
     * @return DetectedCompany[]
     */
    public function getOrderedByIdWithOffset(int $offset): array
    {
        global $wpdb;

        $rows = $wpdb->get_results(
            "SELECT * FROM {$this->tableName} ORDER BY `id` DESC LIMIT {$offset}, " . PHP_INT_MAX
        );

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

    private function mapToEntity(stdClass $row): DetectedCompany
    {
        return new DetectedCompany(
            (int) $row->id,
            $row->name,
            (int) $row->views,
            $row->branch,
        );
    }
}
