<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Data;

use WMIP2C\Database\Models\ConditionCombination;
use WMIP2C\Database\Models\Operator;

final class CombinationOperatorAggregate
{
    private ConditionCombination $conditionCombination;
    private Operator $operator;

    public function __construct(
        ConditionCombination $conditionCombination,
        Operator $operator
    ) {
        $this->conditionCombination = $conditionCombination;
        $this->operator = $operator;
    }
    public function getConditionCombination(): ConditionCombination
    {
        return $this->conditionCombination;
    }

    public function getOperator(): Operator
    {
        return $this->operator;
    }
}