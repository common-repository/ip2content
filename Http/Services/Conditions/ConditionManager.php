<?php

namespace WMIP2C\Http\Services\Conditions;

use Throwable;
use WMIP2C\Database\Models\Condition;
use WMIP2C\Database\Repositories\ConditionRepository;
use WMIP2C\Database\Repositories\ConditionWithCombinationRepository;
use WMIP2C\Http\Data\ConditionData;
use WMIP2C\Http\Data\ConditionEditData;
use WMIP2C\Http\Data\ConditionsPaginationData;
use WMIP2C\Http\Data\PaginationData;
use WMIP2C\Http\Data\TopConditionData;
use WMIP2C\Http\Exceptions\ConditionNotFoundException;
use WMIP2C\Http\Exceptions\FieldNotFoundException;
use WMIP2C\Http\Services\CacheService;

final class ConditionManager
{
    private ConditionRepository $conditionsRepository;
    private ConditionCombinationManager $conditionCombinationManager;

    private ConditionWithCombinationRepository $conditionWithCombinationRepository;

    private CacheService $cacheService;

    public function __construct()
    {
        $this->conditionsRepository = new ConditionRepository();
        $this->conditionCombinationManager = new ConditionCombinationManager();
        $this->conditionWithCombinationRepository = new ConditionWithCombinationRepository();
        $this->cacheService = new CacheService();
    }

    /**
     * @throws FieldNotFoundException
     */
    public function createCondition(array $attributes): Condition
    {
        global $wpdb;

        try {
            $wpdb->query("START TRANSACTION");

            $condition = $this->conditionsRepository->store([
                'label'              => $attributes['label'],
                'field_id'           => $attributes['field_id'],
                'default_content_id' => $attributes['default_content_id'],
                'active'             => $attributes['active']
            ]);

            $conditionCombinations = $this->conditionCombinationManager->storeCombinationsForCondition(
                $attributes['conditions'],
                $condition
            );

            $wpdb->query('COMMIT');

            $condition->setCombinations($conditionCombinations);

            return $condition;
        } catch (Throwable $exception) {
            $wpdb->query('ROLLBACK');

            throw $exception;
        }
    }

    public function getAllConditionsPaginated(PaginationData $paginationData): ConditionsPaginationData
    {
        $page = $paginationData->getPage();
        $perPage = $paginationData->getPerPage();
        $totalCount = $this->conditionsRepository->getTotalCount();
        $totalPages = (int)ceil($totalCount / $perPage);

        $pagination = new PaginationData($page, $perPage, $totalPages, $totalCount);

        $conditionsData = [];
        $rows = $this->conditionsRepository->getAllWithFieldsWithOffsetAndLimit($perPage, $paginationData->getOffset());

        foreach ($rows as $row) {
            $conditionsData[] = new ConditionData(
                (int) $row->id,
                $row->label,
                $row->field_alias,
                (bool) $row->active,
                $row->updated_at
            );
        }

        return new ConditionsPaginationData($conditionsData, $pagination);
    }

    /**
     * @return TopConditionData[]
     */
    public function getTopConditions(): array
    {
        $conditionsData = [];
        $rows = $this->conditionsRepository->getTenOrderedByHitsDescWithFieldsAliases();

        foreach ($rows as $row) {
            $conditionsData[] = new TopConditionData(
                (int) $row->id,
                $row->label,
                $row->field_alias,
                (int) $row->hits,
                (bool) $row->active,
                $row->updated_at
            );
        }

        return $conditionsData;
    }

    /**
     * @throws ConditionNotFoundException
     */
    public function deleteConditionById(int $conditionId): void
    {
        $this->conditionsRepository->deleteById($conditionId);
    }

    /**
     * @throws ConditionNotFoundException
     * @throws FieldNotFoundException
     */
    public function getConditionForEditById(int $conditionId): ConditionEditData
    {
        $condition = $this->conditionsRepository->getById($conditionId);

        if ($condition === null) {
            throw new ConditionNotFoundException();
        }

        return new ConditionEditData(
            $condition->getId(),
            $condition->getDefaultContentId(),
            $condition->getLabel(),
            $condition->getFieldId(),
            $condition->isActive(),
            $this->conditionCombinationManager->getConditionCombinationsForEdit($condition)
        );
    }

    /**
     * @throws ConditionNotFoundException
     * @throws FieldNotFoundException
     */
    public function updateConditionById(int $conditionId, array $attributes): void
    {
        $condition = $this->conditionsRepository->getById($conditionId);

        if ($condition === null) {
            throw new ConditionNotFoundException();
        }

        $this->conditionsRepository->updateById($conditionId, [
            'label'              => $attributes['label'],
            'field_id'           => $attributes['field_id'],
            'default_content_id' => $attributes['default_content_id'],
            'active'             => $attributes['active']
        ]);

        $result = $this->conditionWithCombinationRepository->getActiveWhereIdIn([$conditionId]);
        $this->cacheService->set(sprintf('condition.%d', $conditionId), serialize($result[0]));

        $this->conditionCombinationManager->updateConditionCombinations($conditionId, $attributes);
    }

    public function toggleConditionState(int $conditionId, bool $status): void
    {
        $this->conditionsRepository->updateStatusById($conditionId, $status);
    }
}
