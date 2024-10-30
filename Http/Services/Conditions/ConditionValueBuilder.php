<?php

declare(strict_types=1);

namespace WMIP2C\Http\Services\Conditions;

use WMIP2C\Common\Enums\ConditionFields;
use WMIP2C\Database\Repositories\FieldRepository;
use WMIP2C\Http\Services\ConditionsWorkflow\OperatorValues\UTMTagValue;
use WMIP2C\Http\Services\ConditionsWorkflow\OperatorValues\ZipCodeValue;

final class ConditionValueBuilder
{
    private FieldRepository $fieldsRepository;

    public function __construct()
    {
        $this->fieldsRepository = new FieldRepository();
    }

    public function buildByField(array $conditionAttributes, int $fieldId): string
    {
        $field = $this->fieldsRepository->getById($fieldId);

        if ($field === null) {
            return $this->trimWhitespacesAroundValues($conditionAttributes['value']);
        }

        if ($field->getAlias() === ConditionFields::ZIP_CODE) {
            $zipCodeValue = new ZipCodeValue($conditionAttributes['zip_country'], $conditionAttributes['value']);

            return $this->trimWhitespacesAroundValues($zipCodeValue->makeString());
        }

        if ($field->getAlias() === ConditionFields::UTM) {
            $utmTagValue = new UTMTagValue($conditionAttributes['utm_tag'], $conditionAttributes['value']);

            return $this->trimWhitespacesAroundValues($utmTagValue->makeString());
        }

        return $this->trimWhitespacesAroundValues($conditionAttributes['value']);
    }

    private function trimWhitespacesAroundValues(string $input): string
    {
        $values = explode(',', $input);
        $trimmedValues = [];

        foreach ($values as $value) {
            $trimmedValues[] = trim($value);
        }

        return implode(',', $trimmedValues);
    }
}