<?php

declare(strict_types=1);

namespace WMIP2C\Http\Services\ConditionsWorkflow;

use WMIP2C\Database\Repositories\ConditionWithCombinationRepository;
use WMIP2C\Http\Data\ConditionSourcesData;
use WMIP2C\Http\Services\ConditionsWorkflow\ConditionValueProviders\ConditionValueProvider;
use WMIP2C\Http\Services\ConditionsWorkflow\ConditionValueProviders\ConditionValueProviderProxy;
use WMIP2C\Http\Services\ConditionsWorkflow\ConditionValueProviders\IP2CValueProvider;
use WMIP2C\Http\Services\ConditionsWorkflow\ConditionValueProviders\UTMValueProvider;
use WMIP2C\Http\Services\IP2Company\AdminPanelCompanyParser;
use WMIP2C\Http\Services\IP2Company\CompanyParser;
use WMIP2C\Http\Services\IP2Company\IP2CompanyClient;
use WMIP2C\Http\Services\Statistics\ConditionsStatisticsManager;

final class ConditionReactorBuilder
{
    public function build(ConditionSourcesData $conditionSourcesData): ConditionReactor
    {
        $conditionsStatisticsManager = new ConditionsStatisticsManager();
        $conditionWithOperatorAndFieldRepository = new ConditionWithCombinationRepository();

        $conditionValueProvider = $this->buildValueProvider($conditionSourcesData);

        return new ConditionReactor(
            $conditionValueProvider,
            $conditionsStatisticsManager,
            $conditionWithOperatorAndFieldRepository
        );
    }

    private function buildValueProvider(ConditionSourcesData $conditionSourcesData): ConditionValueProvider
    {
        $queryString = $conditionSourcesData->getQueryString();
        $companyParser = $this->buildCompanyParser($conditionSourcesData);

        return new ConditionValueProviderProxy([
            new IP2CValueProvider($companyParser),
            new UTMValueProvider($queryString)
        ]);
    }

    private function buildCompanyParser(ConditionSourcesData $conditionSourcesData): CompanyParser
    {
        if ($conditionSourcesData->getUserId() !== 0) {
            $queryString = $conditionSourcesData->getQueryString();

            return AdminPanelCompanyParser::fromQueryString($queryString);
        }

        return new IP2CompanyClient();
    }
}
