<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Mapper;

use WMIP2C\Http\Data\TopConditionData;

final class TopConditionsCollectionMapper
{
    /**
     * @param TopConditionData[] $topConditionData
     *
     * @return array
     */
    public function mapDataCollectionToArray(array $topConditionData): array
    {
        $conditions = [];

        foreach ($topConditionData as $conditionData) {
            $conditions[] = [
                'condition' => $conditionData->getName(),
                'views' => $conditionData->getHits(),
                'type' => $conditionData->getFieldAlias(),
            ];
        }

        return $conditions;
    }
}