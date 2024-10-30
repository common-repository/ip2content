<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Data;

use WMIP2C\Database\Models\Condition;
use WMIP2C\Database\Models\Field;

final class ConditionCombinationOperatorFieldAggregate
{
    private Condition $condition;
    private Field $field;

    /**
     * @var CombinationOperatorAggregate[]
     */
    private array $combinations;

    public function __construct(
        Condition $condition,
        Field $field,
        array $combinations
    ) {
        $this->condition = $condition;
        $this->field = $field;
        $this->combinations = $combinations;
    }

    public function getCondition(): Condition
    {
        return $this->condition;
    }

    public function getField(): Field
    {
        return $this->field;
    }

    /**
     * @return CombinationOperatorAggregate[]
     */
    public function getCombinations(): array
    {
        return $this->combinations;
    }
}
