<?php

declare(strict_types=1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\ConditionValueProviders;

use WMIP2C\Database\Models\Field;

interface ConditionValueProvider
{
    public function getValueByField(Field $field): ?string;
}
