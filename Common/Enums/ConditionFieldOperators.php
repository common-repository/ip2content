<?php

namespace WMIP2C\Common\Enums;

final class ConditionFieldOperators
{
    public const STRING_EQUAL = 'string_equal';
    public const STRING_STARTS_WITH = 'string_starts_with';
    public const STRING_ENDS_WITH = 'string_ends_with';
    public const STRING_CONTAINS = 'string_contains';
    public const INT_RANGE = 'int_range';
    public const ZIPCODE_EQUAL = 'zipcode_equal';
    public const ZIPCODE_STARTS_WITH = 'zipcode_starts_with';
    public const ZIPCODE_ENDS_WITH = 'zipcode_ends_with';
    public const ZIPCODE_CONTAINS = 'zipcode_contains';
    public const ZIPCODE_RANGE = 'zipcode_range';
    public const UTM_EQUAL = 'utm_equal';
    public const UTM_STARTS_WITH = 'utm_starts_with';
    public const UTM_ENDS_WITH = 'utm_ends_with';
    public const UTM_CONTAINS = 'utm_contains';
    public const INDUSTRY_EQUAL = 'industry_equal';
}