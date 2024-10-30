<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\Operators;

final class StringIsOneOf implements Assert
{
    public function match(string $expected, string $actual, array $meta = []): bool
    {
        $actual = strtolower($actual);
        $expectedValues = explode(',', $expected);

        foreach ($expectedValues as $index => $expectedValue) {
            $expectedValues[$index] = strtolower($expectedValue);
        }

        return in_array($actual, $expectedValues, true);
    }
}