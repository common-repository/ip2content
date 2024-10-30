<?php

declare(strict_types=1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\OperatorValues;

final class UTMTagValue
{
    private string $label;
    private string $value;

    public function __construct(string $label, string $value)
    {
        $this->label = $label;
        $this->value = $value;
    }

    public function makeString(): string
    {
        return $this->label . '&' . $this->value;
    }
}
