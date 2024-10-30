<?php

declare(strict_types=1);

namespace WMIP2C\Http\Services\Conditions;

use WMIP2C\Common\Enums\ConditionFieldSources;
use WMIP2C\Common\Enums\WordpressOptions;
use WMIP2C\Database\Repositories\FieldRepository;

final class ConditionFieldsManager
{
    private FieldRepository $fieldsRepository;

    public function __construct()
    {
        $this->fieldsRepository = new FieldRepository();
    }

    public function getFieldsList(): array
    {
        $list = [];
        $fields = $this->fieldsRepository->getAll();
        $isIP2CEnabled = get_option(WordpressOptions::IP2C_ACTIVE);
        $isUTMTRackingEnabled = get_option(WordpressOptions::UTM_TRACKING_ACTIVE);

        foreach ($fields as $field) {
            $disabled = false;

            $ip2cDisabled = !$isIP2CEnabled && $field->getSource() === ConditionFieldSources::IP2C;
            $utmDisabled = !$isUTMTRackingEnabled && $field->getSource() === ConditionFieldSources::UTM;

            if ($ip2cDisabled || $utmDisabled) {
                $disabled = true;
            }

            $list[] = [
                'value' => $field->getId(),
                'text' => $field->getAlias(),
                'default_operator_id' => $field->getDefaultOperatorId(),
                'disabled' => $disabled,
            ];
        }

        return $list;
    }
}
