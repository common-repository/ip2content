<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Services\ConditionsWorkflow\ConditionValueProviders;

use WMIP2C\Common\Enums\ConditionFields;
use WMIP2C\Common\Enums\ConditionFieldSources;
use WMIP2C\Common\Enums\WordpressOptions;
use WMIP2C\Database\Models\Field;
use WMIP2C\Http\Exceptions\IP2CompanyClientException;
use WMIP2C\Http\Exceptions\IP2CompanyException;
use WMIP2C\Http\Services\ConditionsWorkflow\OperatorValues\ZipCodeValue;
use WMIP2C\Http\Services\IP2Company\CompanyParser;

final class IP2CValueProvider implements ConditionValueProvider
{
    private CompanyParser $companyParser;

    public function __construct(CompanyParser $companyParser)
    {
        $this->companyParser = $companyParser;
    }

    /**
     * @throws IP2CompanyClientException
     */
    public function getValueByField(Field $field): ?string
    {
        if ($field->getSource() !== ConditionFieldSources::IP2C) {
            return null;
        }

        $fieldAlias = $field->getAlias();

        try {
            $company = $this->companyParser->getCompanyByIP();
        } catch (IP2CompanyException $exception) {
            return '';
        }

        $normalizedCompany = $this->normalizeCompanyData($company);

        return $normalizedCompany[$fieldAlias] ?? null;
    }

    private function normalizeCompanyData(array $company): array
    {
        $zipCodeValue = new ZipCodeValue($company[ConditionFields::COUNTRY], $company[ConditionFields::ZIP_CODE]);
        $company[ConditionFields::ZIP_CODE] = $zipCodeValue->makeString();

        return $company;
    }
}
