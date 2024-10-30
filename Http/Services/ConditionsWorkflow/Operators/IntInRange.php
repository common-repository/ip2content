<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\Operators;

final class IntInRange implements Assert
{
    public function match(string $expected, string $actual, array $meta = []): bool
    {
        $actualInt = (int) $actual;
        [$from, $to] = explode(',', $expected);

        $intFrom = (int) $from;
        $intTo = (int) $to;

        return $actualInt >= $intFrom && $actualInt <= $intTo;
    }
}