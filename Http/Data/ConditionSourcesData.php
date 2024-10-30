<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Data;

final class ConditionSourcesData
{
    private int $userId;
    private ?string $queryString;

    public function __construct(
        int $userId,
        ?string $queryString
    ) {
        $this->userId = $userId;
        $this->queryString = $queryString;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getQueryString(): ?string
    {
        return $this->queryString;
    }
}