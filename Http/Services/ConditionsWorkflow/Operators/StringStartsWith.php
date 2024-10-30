<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\Operators;

final class StringStartsWith implements Assert
{
    public function match(string $expected, string $actual, array $meta = []): bool
    {
        $expectedValues = explode(',', $expected);
        $startsWithAnyOfRegExp = $this->buildStartsWithAnyOfRegExp($expectedValues);

        return preg_match($startsWithAnyOfRegExp, $actual) !== 0;
    }

    private function buildStartsWithAnyOfRegExp(array $values): string
    {
        $stringStartsWith = [];

        foreach ($values as $value) {
            $stringStartsWith[] = "(^{$value}.*)";
        }

        return '/' . implode('|', $stringStartsWith) . '/i';
    }
}