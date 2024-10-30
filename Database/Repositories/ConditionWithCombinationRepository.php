<?php

declare (strict_types = 1);

namespace WMIP2C\Database\Repositories;

use stdClass;
use WMIP2C\Database\Migrations\CreateConditionCombinationsTableMigration;
use WMIP2C\Database\Migrations\CreateOperatorsTableMigration;
use WMIP2C\Database\Models\ConditionCombination;
use WMIP2C\Database\Models\Operator;
use WMIP2C\Http\Data\CombinationOperatorAggregate;
use WMIP2C\Http\Data\ConditionCombinationOperatorFieldAggregate;
use WMIP2C\Http\Data\ConditionFieldAggregate;
final class ConditionWithCombinationRepository
{
    private string $conditionCombinationsTable;
    private ConditionWithOperatorAndFieldRepository $conditionWithOperatorAndFieldRepository;


    public function __construct()
    {
        $this->conditionCombinationsTable = CreateConditionCombinationsTableMigration::getTableName();
        $this->conditionWithOperatorAndFieldRepository = new ConditionWithOperatorAndFieldRepository();
    }

    /**
     * @return ConditionCombinationOperatorFieldAggregate[]
     */
    public function getActiveWhereIdIn(array $conditionsIds): array
    {
        $conditions = $this->conditionWithOperatorAndFieldRepository->getActiveByIds($conditionsIds);

        $conditionsIds = array_map(
            static fn(ConditionFieldAggregate $condition) => $condition->getCondition()->getId(),
            $conditions
        );

        $conditionsIdsSet = implode(',', $conditionsIds);

        if (empty($conditionsIds)) {
            return [];
        }

        global $wpdb;

        $operatorsTable = CreateOperatorsTableMigration::getTableName();
        $conditionCombinationsRows = $wpdb->get_results("
            SELECT
                {$this->conditionCombinationsTable}.id AS combination_id,
                {$this->conditionCombinationsTable}.condition_id AS combination_condition_id,
                {$this->conditionCombinationsTable}.operator_id AS combination_operator_id,
                {$this->conditionCombinationsTable}.content_id AS combination_content_id,
                {$this->conditionCombinationsTable}.value AS combination_value,
                {$this->conditionCombinationsTable}.order AS combination_order,
                {$operatorsTable}.id AS operator_id,
                {$operatorsTable}.alias AS operator_alias,
                {$operatorsTable}.name_en AS operator_name_en,
                {$operatorsTable}.name_de AS operator_name_de,
                {$operatorsTable}.class AS operator_class
            FROM
                {$this->conditionCombinationsTable}
                JOIN {$operatorsTable} ON {$this->conditionCombinationsTable}.operator_id = {$operatorsTable}.id
            WHERE
                {$this->conditionCombinationsTable}.condition_id IN ({$conditionsIdsSet})
            ORDER BY
                {$this->conditionCombinationsTable}.order
        ");

        $conditionCombinations = $this->mapToConditionsCombinations($conditionCombinationsRows);

        $groupedConditionCombinations = [];

        foreach ($conditionCombinations as $conditionCombination) {
            $conditionId = $conditionCombination->getConditionCombination()->getConditionId();

            $groupedConditionCombinations[$conditionId][] = $conditionCombination;
        }

        $conditionsWithCombinations = [];

        foreach ($conditions as $id => $conditionAggregate) {
            $conditionsWithCombinations[] = new ConditionCombinationOperatorFieldAggregate(
                $conditionAggregate->getCondition(),
                $conditionAggregate->getField(),
                $groupedConditionCombinations[$id]
            );
        }

        return $conditionsWithCombinations;
    }

    /**
     * @return CombinationOperatorAggregate[]
     */
    private function mapToConditionsCombinations(array $rows): array
    {
        $combinations = [];

        foreach ($rows as $row) {
            $operator = $this->mapToOperator($row);
            $conditionCombination = $this->mapToConditionCombination($row);

            $combinations[] = new CombinationOperatorAggregate(
                $conditionCombination,
                $operator
            );
        }

        return $combinations;
    }

    private function mapToConditionCombination(stdClass $row): ConditionCombination
    {
        return new ConditionCombination(
            (int) $row->combination_id,
            (int) $row->combination_condition_id,
            (int) $row->combination_operator_id,
            (int) $row->combination_content_id,
            $row->combination_value,
            (int) $row->combination_order
        );
    }

    private function mapToOperator(stdClass $row): Operator
    {
        return new Operator(
            (int) $row->operator_id,
            $row->operator_alias,
            $row->operator_name_en,
            $row->operator_name_de,
            $row->operator_class
        );
    }
}
