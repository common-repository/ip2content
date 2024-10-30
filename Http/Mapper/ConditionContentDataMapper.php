<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Mapper;

use WMIP2C\Http\Services\ConditionsWorkflow\ConditionContentBag;

final class ConditionContentDataMapper
{
    public function mapConditionContentBagToArray(ConditionContentBag $conditionContentBag): array
    {
        $mappedCollection = [];

        foreach ($conditionContentBag->all() as $conditionId => $contentId) {
            $mappedCollection[] = [
                'id' => $conditionId,
                'render_content_id' => $contentId,
            ];
        }

        return $mappedCollection;
    }
}
