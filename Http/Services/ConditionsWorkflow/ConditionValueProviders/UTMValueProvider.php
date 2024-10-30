<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\ConditionValueProviders;

use WMIP2C\Common\Enums\ConditionFieldSources;
use WMIP2C\Common\Enums\WordpressOptions;
use WMIP2C\Database\Models\Field;

final class UTMValueProvider implements ConditionValueProvider
{
    private string $queryString;

    public function __construct(string $queryString)
    {
        $this->queryString = $queryString;
    }

    public function getValueByField(Field $field): ?string
    {
        if (!get_option(WordpressOptions::UTM_TRACKING_ACTIVE, false)) {
            return null;
        }

        if ($field->getSource() !== ConditionFieldSources::UTM) {
            return null;
        }

        return $this->queryString;
    }
}