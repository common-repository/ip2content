<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Data;

final class ConditionsPaginationData
{
    /**
     * @var ConditionData[]
     */
    private array $conditions;
    private PaginationData $paginationData;

    public function __construct(array $conditions, PaginationData $paginationData)
    {
        $this->conditions = $conditions;
        $this->paginationData = $paginationData;
    }

    public function getConditionsData(): array
    {
        return $this->conditions;
    }

    public function getPaginationData(): PaginationData
    {
        return $this->paginationData;
    }
}