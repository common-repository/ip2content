<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\Operators;

final class StringEndsWith implements Assert
{
    public function match(string $expected, string $actual, array $meta = []): bool
    {
        $expectedValues = explode(',', $expected);
        $endsWithAnyOfRegExp = $this->buildEndsWithAnyOfRegExp($expectedValues);

        return preg_match($endsWithAnyOfRegExp, $actual) !== 0;
    }

    private function buildEndsWithAnyOfRegExp(array $values): string
    {
        $stringEndsWith = [];

        foreach ($values as $value) {
            $stringEndsWith[] = "(.*{$value}$)";
        }

        return '/' . implode('|', $stringEndsWith) . '/i';
    }
}