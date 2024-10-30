<?php

declare (strict_types = 1);

namespace WMIP2C\Http\Data;

final class StatisticsResultsData
{
    private int $totalViews;
    private int $totalHits;
    private int $ip2CHits;
    private int $companiesDetected;
    private int $tokenLimit;
    private int $tokenLeft;

    public function __construct(
        int $totalViews,
        int $totalHits,
        int $ip2CHits,
        int $companiesDetected,
        int $tokenLimit,
        int $tokenLeft
    ) {
        $this->totalViews = $totalViews;
        $this->totalHits = $totalHits;
        $this->ip2CHits = $ip2CHits;
        $this->companiesDetected = $companiesDetected;
        $this->tokenLimit = $tokenLimit;
        $this->tokenLeft = $tokenLeft;
    }

    public function getTotalViews(): int
    {
        return $this->totalViews;
    }

    public function getTotalHits(): int
    {
        return $this->totalHits;
    }

    public function getIp2CHits(): int
    {
        return $this->ip2CHits;
    }

    public function getCompaniesDetected(): int
    {
        return $this->companiesDetected;
    }

    public function getTokenLimit(): int
    {
        return $this->tokenLimit;
    }

    public function getTokenLeft(): int
    {
        return $this->tokenLeft;
    }
}