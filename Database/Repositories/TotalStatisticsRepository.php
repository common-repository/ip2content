<?php

namespace WMIP2C\Database\Repositories;

use stdClass;
use WMIP2C\Database\Migrations\CreateTotalStatisticsTableMigration;
use WMIP2C\Database\Models\TotalStatistics;

final class TotalStatisticsRepository
{
    private string $tableName;

    public function __construct()
    {
        $this->tableName = CreateTotalStatisticsTableMigration::getTableName();
    }

    public function createEmpty(): TotalStatistics
    {
        global $wpdb;

        $wpdb->insert($this->tableName, [
            'total_views' => 0,
            'total_hits' => 0,
            'ip2c_hits' => 0,
            'companies_detected' => 0,
        ]);

        return new TotalStatistics($wpdb->insert_id, 0, 0, 0, 0);
    }

    public function updateById(int $id, array $attributes): void
    {
        global $wpdb;

        $wpdb->update($this->tableName, $attributes, ['id' => $id]);
    }

    public function getFirst(): ?TotalStatistics
    {
        global $wpdb;

        $rows = $wpdb->get_results("SELECT * FROM {$this->tableName} LIMIT 1");

        if (!isset($rows[0])) {
            return null;
        }

        return $this->mapToEntity($rows[0]);
    }

    private function mapToEntity(stdClass $row): TotalStatistics
    {
        return new TotalStatistics(
            (int) $row->id,
            (int) $row->total_views,
            (int) $row->total_hits,
            (int) $row->ip2c_hits,
            (int) $row->companies_detected,
        );
    }
}