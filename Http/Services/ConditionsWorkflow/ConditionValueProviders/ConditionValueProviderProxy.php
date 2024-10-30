<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\ConditionValueProviders;

use WMIP2C\Database\Models\Field;

class ConditionValueProviderProxy implements ConditionValueProvider
{
    /**
     * @var ConditionValueProvider[]
     */
    private array $providers;

    /**
     * @param ConditionValueProvider[] $providers
     */
    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    public function getValueByField(Field $field): ?string
    {
        foreach ($this->providers as $provider) {
            $value = $provider->getValueByField($field);

            if ($value !== null) {
                return $value;
            }
        }

        return null;
    }
}