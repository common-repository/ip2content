<?php

declare(strict_types=1);

namespace WMIP2C\Http\Services\Statistics;

use WMIP2C\Common\Enums\ConditionFieldSources;
use WMIP2C\Common\Enums\IP2CStatusFields;
use WMIP2C\Database\Models\Condition;
use WMIP2C\Database\Models\Field;
use WMIP2C\Database\Repositories\ConditionRepository;
use WMIP2C\Database\Repositories\TotalStatisticsRepository;
use WMIP2C\Http\Data\StatisticsResultsData;
use WMIP2C\Http\Exceptions\FieldNotFoundException;
use WMIP2C\Http\Services\IP2Company\IP2CompanyClient;

final class ConditionsStatisticsManager
{
    private IP2CompanyClient $ip2CompanyClient;
    private ConditionRepository $conditionRepository;
    private TotalStatisticsRepository $totalStatisticsRepository;

    public function __construct()
    {
        $this->ip2CompanyClient = new IP2CompanyClient();
        $this->conditionRepository = new ConditionRepository();
        $this->totalStatisticsRepository = new TotalStatisticsRepository();
    }

    public function getStatistics(): StatisticsResultsData
    {
        $statistics = $this->totalStatisticsRepository->getFirst();

        if ($statistics === null) {
            $statistics = $this->totalStatisticsRepository->createEmpty();
        }

        $ip2CompanyStatus = $this->ip2CompanyClient->getIP2CompanyStatus();

        return new StatisticsResultsData(
            $statistics->getTotalViews(),
            $statistics->getTotalHits(),
            $statistics->getIp2cHits(),
            $statistics->getCompaniesDetected(),
            $ip2CompanyStatus[IP2CStatusFields::REQUEST_LIMIT] ?? 0,
            $ip2CompanyStatus[IP2CStatusFields::REQUEST_AVAILABLE] ?? 0
        );
    }

    /**
     * @throws FieldNotFoundException
     */
    public function incrementConditionStatistics(Condition $condition, Field $field): void
    {
        $totalStatistics = $this->totalStatisticsRepository->getFirst();

        if ($totalStatistics === null) {
            $totalStatistics = $this->totalStatisticsRepository->createEmpty();
        }

        $ip2cHits = $totalStatistics->getIp2cHits();

        if ($field->getSource() === ConditionFieldSources::IP2C) {
            $ip2cHits++;
        }

        $this->totalStatisticsRepository->updateById(
            $totalStatistics->getId(),
            [
                'total_hits' => $totalStatistics->getTotalHits() + 1,
                'ip2c_hits' => $ip2cHits,
            ]
        );

        $this->conditionRepository->updateById($condition->getId(), ['hits' => $condition->getHits() + 1]);
    }

    public function updateStatistics(
        int $totalViewsDelta = 0,
        int $totalCompaniesDetectedDelta = 0,
        int $totalHitsDelta = 0,
        int $ip2CHitsDelta = 0
    ): void {
        $totalStatistics = $this->totalStatisticsRepository->getFirst();

        if ($totalStatistics === null) {
            $totalStatistics = $this->totalStatisticsRepository->createEmpty();
        }

        $this->totalStatisticsRepository->updateById($totalStatistics->getId(), [
            'total_views' => $totalStatistics->getTotalViews() + $totalViewsDelta,
            'total_hits' => $totalStatistics->getTotalHits() + $totalHitsDelta,
            'ip2c_hits' => $totalStatistics->getIp2cHits() + $ip2CHitsDelta,
            'companies_detected' => $totalStatistics->getCompaniesDetected() + $totalCompaniesDetectedDelta
        ]);
    }
}
