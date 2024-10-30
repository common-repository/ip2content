<?php

namespace WMIP2C\Database\Seeders;

use WMIP2C\Database\Repositories\TotalStatisticsRepository;
use WMIP2C\Database\Seeders\Components\AbstractSeeder;

final class TotalStatisticsSeeder extends AbstractSeeder
{
    public function run(): void
    {
        (new TotalStatisticsRepository())->createEmpty();
    }
}