<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\Operators;

final class StringContains implements Assert
{
    public function match(string $expected, string $actual, array $meta = []): bool
    {
        $expectedValues = explode(',', $expected);
        $containsAnyOfRegExp = $this->buildContainAnyOfRegExp($expectedValues);

        return preg_match($containsAnyOfRegExp, $actual) !== 0;
    }

    private function buildContainAnyOfRegExp(array $values): string
    {
        $stringContains = [];

        foreach ($values as $value) {
            if (empty($value)) {
                continue;
            }

            $stringContains[] = "(.*{$value}.*)";
        }

        return '/' . implode('|', $stringContains) . '/i';
    }
}