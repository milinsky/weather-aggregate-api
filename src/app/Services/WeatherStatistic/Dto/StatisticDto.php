<?php

declare(strict_types=1);

namespace App\Services\WeatherStatistic\Dto;

class StatisticDto
{
    public string $status;
    public string $error;
    public string $mustPopularProvider;
}
