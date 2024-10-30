<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\Conditions;

use WMIP2C\Database\Repositories\OperatorRepository;

final class FieldOperatorsManager
{
    private OperatorRepository $operatorsRepository;

    public function __construct()
    {
        $this->operatorsRepository = new OperatorRepository();
    }

    public function getOperatorsListByField(int $fieldId): array
    {
        $list = [];
        $operators = $this->operatorsRepository->getByFieldId($fieldId);

        foreach ($operators as $operator) {
            $list[] = [
                'value' => $operator->getId(),
                'text' => $operator->getName(),
                'alias' => $operator->getAlias(),
            ];
        }

        return $list;
    }
}