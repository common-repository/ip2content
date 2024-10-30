<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Mapper;

use WMIP2C\Http\Data\ConditionsPaginationData;

final class ConditionsPaginationDataMapper
{
    private ConditionDataMapper $conditionMapper;
    private PaginationDataMapper $paginationDataMapper;

    public function __construct()
    {
        $this->conditionMapper = new ConditionDataMapper();
        $this->paginationDataMapper = new PaginationDataMapper();
    }

    public function mapDataToArray(ConditionsPaginationData $conditionsPaginationData): array
    {
        $pagination = $conditionsPaginationData->getPaginationData();
        $conditionsData = $conditionsPaginationData->getConditionsData();

        $items = [];

        foreach ($conditionsData as $conditionData) {
            $items[] = $this->conditionMapper->mapDataToArray($conditionData);
        }

        return [
            'items' => $items,
            'pagination' => $this->paginationDataMapper->mapDataToArray($pagination),
        ];
    }
}