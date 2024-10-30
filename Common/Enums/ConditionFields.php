<?php

namespace WMIP2C\Common\Enums;

final class ConditionFields
{
    public const INDUSTRY = 'branch_code';
    public const COMPANY_NAME = 'name';
    public const COMPANY_SIZE = 'size_class';
    public const COUNTRY = 'country_code';
    public const ZIP_CODE = 'zip';
    public const DOMAIN = 'domain';
    public const REVENUE_CLASS = 'revenue_class';
    public const UTM = 'utm_tag';

    public const FIELDS = [
        self::INDUSTRY,
        self::COMPANY_NAME,
        self::COMPANY_SIZE,
        self::COUNTRY,
        self::ZIP_CODE,
        self::DOMAIN,
        self::REVENUE_CLASS,
        self::UTM,
    ];
}