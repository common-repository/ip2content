<?php

declare(strict_types=1);

namespace WMIP2C\Http\Mapper;

use DateTimeImmutable;
use WMIP2C\Http\Data\ConditionData;

final class ConditionDataMapper
{
    public function mapDataToArray(ConditionData $conditionData): array
    {
        $updatedAtXTimestamp = (new DateTimeImmutable($conditionData->getUpdatedAt()))->getTimestamp() * 1000;

        return [
            'id' => $conditionData->getId(),
            'name' => $conditionData->getName(),
            'field' => $conditionData->getFieldAlias(),
            'active' => $conditionData->isActive(),
            'updated_at' => $updatedAtXTimestamp
        ];
    }
}
