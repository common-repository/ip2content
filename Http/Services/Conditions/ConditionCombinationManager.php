<?php

declare(strict_types=1);

namespace WMIP2C\Http\Services\Conditions;

use WMIP2C\Common\Enums\ConditionFields;
use WMIP2C\Database\Models\Condition;
use WMIP2C\Database\Models\ConditionCombination;
use WMIP2C\Database\Repositories\ConditionCombinationRepository;
use WMIP2C\Database\Repositories\FieldRepository;
use WMIP2C\Http\Data\ConditionCombinationsEditData;
use WMIP2C\Http\Exceptions\FieldNotFoundException;

final class ConditionCombinationManager
{
    private FieldRepository $fieldRepository;
    private ConditionValueBuilder $conditionValueBuilder;
    private ConditionCombinationRepository $conditionCombinationRepository;

    public function __construct()
    {
        $this->fieldRepository = new FieldRepository();
        $this->conditionValueBuilder = new ConditionValueBuilder();
        $this->conditionCombinationRepository = new ConditionCombinationRepository();
    }

    /**
     * @return ConditionCombination[]
     */
    public function storeCombinationsForCondition(array $combinations, Condition $condition): array
    {
        $combinationsAttributes = [];

        foreach ($combinations as $index => $combination) {
            $operatorId = $combination['operator_id'];

            if ($operatorId === null) {
                $operatorId = $this->getDefaultOperatorIdByFieldId($combination['field_id']);
            }

            $combinationsAttributes[] = [
                'condition_id' => $condition->getId(),
                'operator_id'  => $operatorId,
                'content_id'   => $combination['dynamic_content_id'],
                'value'        => $this->conditionValueBuilder->buildByField($combination, $condition->getFieldId()),
                'order'        => $index
            ];
        }

        return $this->conditionCombinationRepository->storeMany($combinationsAttributes);
    }

    public function updateConditionCombinations(int $conditionId, array $conditionAttributes): void
    {
        $combinationsAttributes = $conditionAttributes['conditions'];
        $combinationsIds = array_column($combinationsAttributes, 'id');

        $this->conditionCombinationRepository->deleteByConditionIdWhereIdNotIn($conditionId, $combinationsIds);

        foreach ($combinationsAttributes as $index => $combinationAttributes) {
            $operatorId = $combinationAttributes['operator_id'];

            if ($operatorId === null) {
                $operatorId = $this->getDefaultOperatorIdByFieldId($conditionAttributes['field_id']);
            }

            $value = $this->conditionValueBuilder->buildByField(
                $combinationAttributes,
                $conditionAttributes['field_id']
            );

            $attributes = [
                'condition_id' => $conditionId,
                'operator_id'  => $operatorId,
                'content_id'   => $combinationAttributes['dynamic_content_id'],
                'value'        => $value,
                'order'        => $index
            ];

            if (!isset($combinationAttributes['id'])) {
                $this->conditionCombinationRepository->store($attributes);

                continue;
            }

            $this->conditionCombinationRepository->updateById($combinationAttributes['id'], $attributes);
        }
    }

    /**
     * @return ConditionCombinationsEditData[]
     */
    public function getConditionCombinationsForEdit(Condition $condition): array
    {
        $field = $this->fieldRepository->getById($condition->getFieldId());

        if ($field === null) {
            throw new FieldNotFoundException();
        }

        $conditionCombinations = $this->conditionCombinationRepository->getByConditionId($condition->getId());

        $conditionCombinationsEditData = [];

        foreach ($conditionCombinations as $conditionCombination) {

            $value = $conditionCombination->getValue();

            if ($field->getAlias() === ConditionFields::ZIP_CODE) {
                [$countries, $value] = explode('&', $value);
            } elseif ($field->getAlias() === ConditionFields::UTM) {
                [$utmTag, $value] = explode('&', $value);
            }

            $conditionCombinationsEditData[] = new ConditionCombinationsEditData(
                $conditionCombination->getId(),
                $conditionCombination->getConditionId(),
                $conditionCombination->getOperatorId(),
                $conditionCombination->getContentId(),
                $value,
                $countries ?? null,
                $utmTag ?? null
            );
        }

        return $conditionCombinationsEditData;
    }

    /**
     * @throws FieldNotFoundException
     */
    private function getDefaultOperatorIdByFieldId(int $fieldId): int
    {
        $field = $this->fieldRepository->getById($fieldId);

        if ($field === null) {
            throw new FieldNotFoundException();
        }

        return $field->getDefaultOperatorId();
    }
}
