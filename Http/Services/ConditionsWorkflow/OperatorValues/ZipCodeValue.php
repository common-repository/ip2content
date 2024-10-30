<?php

declare(strict_types=1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\OperatorValues;

final class ZipCodeValue
{
    private string $countries;
    private string $zipCodes;

    public function __construct(string $countries, string $zipCodes)
    {
        $this->countries = $countries;
        $this->zipCodes = $zipCodes;
    }

    public function makeString(): string
    {
        return $this->countries . '&' . $this->zipCodes;
    }
}
