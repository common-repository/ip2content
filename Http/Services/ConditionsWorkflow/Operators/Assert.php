<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\Operators;

interface Assert
{
    public function match(string $expected, string $actual, array $meta = []): bool;
}