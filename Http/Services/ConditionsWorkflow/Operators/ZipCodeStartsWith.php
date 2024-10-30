<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\Operators;

final class ZipCodeStartsWith implements Assert
{
    public function match(string $expected, string $actual, array $meta = []): bool
    {
        [$expectedCountries, $expectedZipCodes] = explode('&', $expected);
        [$actualCountry, $actualZipCode] = explode('&', $actual);

        $stringIsOneOf = new StringIsOneOf();
        $stringStartsWith = new StringStartsWith();

        $countryMatched = $stringIsOneOf->match($expectedCountries, $actualCountry);

        if (!$countryMatched) {
            return false;
        }

        return $stringStartsWith->match($expectedZipCodes, $actualZipCode);
    }
}