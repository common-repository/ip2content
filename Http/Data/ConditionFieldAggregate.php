<?php

declare(strict_types=1);

namespace WMIP2C\Http\Data;

use WMIP2C\Database\Models\Condition;
use WMIP2C\Database\Models\Field;

final class ConditionFieldAggregate
{
    private Condition $condition;
    private Field $field;

    public function __construct(
        Condition $condition,
        Field $field
    ) {
        $this->condition = $condition;
        $this->field = $field;
    }

    public function getCondition(): Condition
    {
        return $this->condition;
    }

    public function getField(): Field
    {
        return $this->field;
    }
}
