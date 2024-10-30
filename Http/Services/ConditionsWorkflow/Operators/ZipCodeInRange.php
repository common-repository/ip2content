<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\Operators;

final class ZipCodeInRange implements Assert
{
    public function match(string $expected, string $actual, array $meta = []): bool
    {
        [$expectedCountries, $expectedZipCodes] = explode('&', $expected);
        [$actualCountry, $actualZipCode] = explode('&', $actual);

        $stringIsOneOf = new StringIsOneOf();
        $intInRange = new IntInRange();

        $countryMatched = $stringIsOneOf->match($expectedCountries, $actualCountry);

        if (!$countryMatched) {
            return false;
        }

        $expectedZipCodeRanges = explode(',', $expectedZipCodes);

        foreach ($expectedZipCodeRanges as $expectedZipCodeRange) {
            $zipCodeRange = str_replace('-', ',', $expectedZipCodeRange);
            $matched = $intInRange->match($zipCodeRange, $actualZipCode);

            if ($matched) {
                return true;
            }
        }

        return false;
    }
}
