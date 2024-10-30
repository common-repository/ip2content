<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\Conditions;

use WMIP2C\Database\Repositories\FieldValueRepository;

final class FieldValuesManager
{
    private FieldValueRepository $fieldValuesRepository;

    public function __construct()
    {
        $this->fieldValuesRepository = new FieldValueRepository();
    }

    public function getValuesListByField(int $fieldId): array
    {
        $list = [];
        $values = $this->fieldValuesRepository->getByFieldId($fieldId);

        foreach ($values as $value) {
            $list[] = [
                'value' => $value->getValue(),
                'text' => $value->getName(),
            ];
        }

        return $list;
    }
}