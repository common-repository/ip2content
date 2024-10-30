<?php

declare(strict_types=1);

namespace WMIP2C\Http\Mapper;

use WMIP2C\Http\Data\StatisticsResultsData;

final class StatisticsResultsDataMapper
{
    public function mapDataToArray(StatisticsResultsData $data): array
    {
        return [
            'total_views'        => $data->getTotalViews(),
            'total_hits'         => $data->getTotalHits(),
            'ip2c_hits'          => $data->getIp2CHits(),
            'companies_detected' => $data->getCompaniesDetected(),
            'token_limit'        => $data->getTokenLimit(),
            'token_left'         => $data->getTokenLeft(),
        ];
    }
}