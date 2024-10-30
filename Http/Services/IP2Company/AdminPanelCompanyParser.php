<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\IP2Company;

use WMIP2C\Common\Enums\ConditionFields;

final class AdminPanelCompanyParser implements CompanyParser
{
    private const COMPANY_NAME = 'company_name';

    private array $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public static function fromQueryString(string $queryString): self
    {
        $queryString = str_replace(['?', '/'], '', $queryString);
        $queryPairs = explode('&', $queryString);

        $queryParams = array_fill_keys(ConditionFields::FIELDS, '');

        foreach ($queryPairs as $pair) {
            [$field, $value] = explode('=', $pair);
            $field = strtolower($field);

            if ($field === self::COMPANY_NAME) {
                $field = ConditionFields::COMPANY_NAME;
            }

            if (!isset($queryParams[$field])) {
                continue;
            }

            $queryParams[$field] = $value;
        }

        return new self($queryParams);
    }

    public function getCompanyByIP(): array
    {
        return $this->attributes;
    }
}