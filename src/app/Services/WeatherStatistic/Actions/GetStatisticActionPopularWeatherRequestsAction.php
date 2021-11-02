<?php

declare(strict_types=1);

namespace App\Services\WeatherStatistic\Actions;

use App\Services\WeatherStatistic\Dto\StatisticDto;
use App\Services\WeatherStatistic\GetStatisticActionInterface;
use App\Services\WeatherStatistic\StatisticRepository;

use DateTimeInterface;

class GetStatisticActionPopularWeatherRequestsAction implements GetStatisticActionInterface
{
    private StatisticRepository $statisticRepository;

    public function __construct(StatisticRepository $statisticRepository)
    {
        $this->statisticRepository = $statisticRepository;
    }

    public function execute(DateTimeInterface $dateTime): StatisticDto
    {
        $statisticDto = new StatisticDto();
        $statisticData = $this->statisticRepository->getRecent($dateTime);
        $statisticDto->count = (string) array_shift($statisticData[0]['topK(1)(provider_name)']);
        return $statisticDto;
    }
}
