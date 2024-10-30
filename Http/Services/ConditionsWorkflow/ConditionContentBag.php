<?php

declare(strict_types=1);

namespace WMIP2C\Http\Services\ConditionsWorkflow;

final class ConditionContentBag
{
    /**
     * @var int[][]
     */
    private array $bag = [];

    public function put(int $key, int $contentId): void
    {
        $this->bag[$key] = $contentId;
    }

    /**
     * @return int[][]
     */
    public function all(): array
    {
        return $this->bag;
    }
}
