<?php

namespace App\Services\WeatherStatistic;

use App\Services\WeatherStatistic\Dto\StatisticDto;
use DateTimeInterface;

interface GetStatisticActionInterface
{
    public function execute(DateTimeInterface $dateTime): StatisticDto;
}
