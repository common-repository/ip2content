<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Controllers\Api;

use Throwable;
use WMIP2C\Http\Exceptions\IP2CompanyClientException;
use WMIP2C\Http\Mapper\DetectedCompaniesCollectionMapper;
use WMIP2C\Http\Mapper\StatisticsResultsDataMapper;
use WMIP2C\Http\Mapper\TopConditionsCollectionMapper;
use WMIP2C\Http\Services\Conditions\ConditionManager;
use WMIP2C\Http\Services\HttpResponse;
use WMIP2C\Http\Services\Statistics\ConditionsStatisticsManager;
use WMIP2C\Http\Services\Statistics\DetectedCompaniesStatisticsManager;
use WP_REST_Request;
use WP_REST_Response;

final class StatisticsApiController extends UserAwareController
{
    private ConditionManager $conditionManager;
    private ConditionsStatisticsManager $totalStatisticsManager;
    private DetectedCompaniesStatisticsManager $companyDetector;
    private StatisticsResultsDataMapper $statisticsResultsDataMapper;
    private TopConditionsCollectionMapper $topConditionsCollectionMapper;
    private DetectedCompaniesCollectionMapper $detectedCompaniesCollectionMapper;

    public function __construct()
    {
        $this->conditionManager = new ConditionManager();
        $this->totalStatisticsManager = new ConditionsStatisticsManager();
        $this->companyDetector = new DetectedCompaniesStatisticsManager();

        $this->statisticsResultsDataMapper = new StatisticsResultsDataMapper();
        $this->topConditionsCollectionMapper = new TopConditionsCollectionMapper();
        $this->detectedCompaniesCollectionMapper = new DetectedCompaniesCollectionMapper();
    }

    public function getResults(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        try {
            $statisticsData = $this->totalStatisticsManager->getStatistics();
        } catch (IP2CompanyClientException $exception) {
            return HttpResponse::failed();
        }

        $statistics = $this->statisticsResultsDataMapper->mapDataToArray($statisticsData);

        return HttpResponse::successful($statistics);
    }

    public function getLatestDetectedCompanies(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        $detectedCompanies = $this->companyDetector->getLatestDetectedCompanies();
        $latestDetectedCompanies = $this->detectedCompaniesCollectionMapper->mapCollectionToArray($detectedCompanies);

        return HttpResponse::successful($latestDetectedCompanies);
    }

    public function getTopConditions(WP_REST_Request $request): WP_REST_Response
    {
        if (!$this->isInternalUser($request)) {
            return HttpResponse::failedNotFound();
        }

        try {
            $conditions = $this->conditionManager->getTopConditions();
            $topConditions = $this->topConditionsCollectionMapper->mapDataCollectionToArray($conditions);
        } catch (Throwable $t) {
            return HttpResponse::failed();
        }

        return HttpResponse::successful($topConditions);
    }
}