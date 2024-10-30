<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\Operators;

final class IndustryIsOneOf implements Assert
{
    public function match(string $expected, string $actual, array $meta = []): bool
    {
        $actual = $this->parseActualValue($actual);

        [$parentCode] = explode('.', $expected);
        $parentCode = ltrim($parentCode, '0');

        return (new StringIsOneOf())->match($parentCode, $actual);
    }

    private function parseActualValue(string $value): string
    {
        if (strpos($value, '.') !== false) {
            [$parentCode] = explode('.', $value);

            return ltrim($parentCode, '0');
        }

        $value = substr($value, 0, 2);

        return ltrim($value, '0');
    }
}