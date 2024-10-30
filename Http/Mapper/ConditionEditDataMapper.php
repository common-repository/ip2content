<?php

declare(strict_types=1);

namespace WMIP2C\Http\Mapper;

use WMIP2C\Http\Data\ConditionEditData;

final class ConditionEditDataMapper
{
    public function mapDataToArray(ConditionEditData $conditionEditData): array
    {
        $conditionAttributes = [
            'id'                 => $conditionEditData->getId(),
            'default_content_id' => $conditionEditData->getDefaultContentId(),
            'label'              => $conditionEditData->getLabel(),
            'field_id'           => $conditionEditData->getFieldId(),
            'active'             => $conditionEditData->isActive(),
            'conditions'         => []
        ];

        $combinationsData = $conditionEditData->getCombinations();

        foreach ($combinationsData as $combinationData) {
            $conditionAttributes['conditions'][] = [
                'id'                 => $combinationData->getId(),
                'operator_id'        => $combinationData->getOperatorId(),
                'dynamic_content_id' => $combinationData->getContentId(),
                'value'              => $combinationData->getValue(),
                'zip_country'      => $combinationData->getCountries(),
                'utm_tag'            => $combinationData->getUtmTag(),
            ];
        }

        return $conditionAttributes;
    }
}
