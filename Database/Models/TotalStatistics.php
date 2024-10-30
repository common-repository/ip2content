<?php

declare (strict_types = 1);

namespace WMIP2C\Database\Models;

final class TotalStatistics
{
    private int $id;
    private int $total_views;
    private int $total_hits;
    private int $ip2c_hits;
    private int $companies_detected;

    public function __construct(
        int $id,
        int $totalViews,
        int $totalHits,
        int $ip2CHits,
        int $companiesDetected
    ) {
        $this->id = $id;
        $this->total_views = $totalViews;
        $this->total_hits = $totalHits;
        $this->ip2c_hits = $ip2CHits;
        $this->companies_detected = $companiesDetected;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTotalViews(): int
    {
        return $this->total_views;
    }

    public function getTotalHits(): int
    {
        return $this->total_hits;
    }

    public function getIp2cHits(): int
    {
        return $this->ip2c_hits;
    }

    public function getCompaniesDetected(): int
    {
        return $this->companies_detected;
    }
}