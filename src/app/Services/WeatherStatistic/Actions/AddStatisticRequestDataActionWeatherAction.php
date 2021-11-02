<?php

declare(strict_types=1);

namespace App\Services\WeatherStatistic\Actions;

use App\Services\WeatherStatistic\AddStatisticDataInterface;
use App\Services\WeatherStatistic\StatisticRepository;

class AddStatisticRequestDataWeatherAction implements AddStatisticDataInterface
{
    private StatisticRepository $statisticRepository;

    public function __construct(StatisticRepository $statisticRepository)
    {
        $this->statisticRepository = $statisticRepository;
    }

    public function execute(string $providerName): void
    {
        $this->statisticRepository->add($providerName);
    }
}
