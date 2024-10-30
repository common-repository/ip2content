<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\Operators;

final class UTMStartsWith implements Assert
{
    public function match(string $expected, string $actual, array $meta = []): bool
    {
        if ($actual === '') {
            return false;
        }

        [$expectedTag, $expectedTagValue] = explode('&', $expected);

        $actualTagValue = $this->getActualTagValueFromUTMString($expectedTag, $actual);

        return (new StringStartsWith())->match($expectedTagValue, $actualTagValue);
    }

    private function getActualTagValueFromUTMString(string $tag, string $utmString): string
    {
        $normalizedUTMString = str_replace(['?', '/'], '', $utmString);
        $utmTags = explode('&', $normalizedUTMString);

        foreach ($utmTags as $utmTag) {
            [$label, $value] = explode('=', $utmTag);

            if ($label === $tag) {
                return $value;
            }
        }

        return '';
    }
}