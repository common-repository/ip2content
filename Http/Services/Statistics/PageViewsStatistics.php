<?php

declare(strict_types=1);

namespace WMIP2C\Http\Services\Statistics;

use WMIP2C\Common\Enums\ConditionFields;
use WMIP2C\Common\Enums\WordpressOptions;
use WMIP2C\Http\Exceptions\IP2CompanyClientException;
use WMIP2C\Http\Exceptions\IP2CompanyException;
use WMIP2C\Http\Services\IP2Company\IP2CompanyClient;

final class PageViewsStatistics
{
    private IP2CompanyClient $ip2CompanyClient;
    private ConditionsStatisticsManager $conditionsStatisticsManager;
    private DetectedCompaniesStatisticsManager $detectedCompaniesStatisticsManager;

    public function __construct()
    {
        $this->ip2CompanyClient = new IP2CompanyClient();
        $this->conditionsStatisticsManager = new ConditionsStatisticsManager();
        $this->detectedCompaniesStatisticsManager = new DetectedCompaniesStatisticsManager();
    }

    public function updateViewsStatistics(): void
    {
        $totalCompaniesDetectedDelta = 0;

        if (get_option(WordpressOptions::COMPANY_DETECTION_ACTIVE)) {
            $totalCompaniesDetectedDelta = $this->detectCompany();
        }

        $this->conditionsStatisticsManager->updateStatistics(1, $totalCompaniesDetectedDelta);
    }

    private function detectCompany(): int
    {
        try {
            $company = $this->ip2CompanyClient->getCompanyByIP();

            if (!isset($company[ConditionFields::COMPANY_NAME], $company[ConditionFields::INDUSTRY])) {
                throw new IP2CompanyClientException();
            }

            $isDetectedNewCompany = $this->detectedCompaniesStatisticsManager->updateCompanyViewsByNameAndIndustry(
                $company[ConditionFields::COMPANY_NAME],
                $company[ConditionFields::INDUSTRY]
            );

            return (int) $isDetectedNewCompany;
        } catch (IP2CompanyException $exception) {
            return 0;
        }
    }
}
