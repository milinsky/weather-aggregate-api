<?php

declare(strict_types=1);

namespace App\Services\WeatherStatistic\Actions;

use App\Services\WeatherStatistic\Dto\StatisticDto;
use App\Services\WeatherStatistic\Enum\ErrorEnum;
use App\Services\WeatherStatistic\Enum\StatusEnum;
use App\Services\WeatherStatistic\GetStatisticActionInterface;
use App\Services\WeatherStatistic\StatisticRepository;
use ErrorException;
use DateTime;

use function array_shift;

class GetStatisticPopularWeatherRequestsAction implements GetStatisticActionInterface
{
    private StatisticRepository $statisticRepository;

    public function __construct(StatisticRepository $statisticRepository)
    {
        $this->statisticRepository = $statisticRepository;
    }

    public function execute(string $period): StatisticDto
    {
        $statisticDto = new StatisticDto();

        if (empty($period)) {
            $statisticDto->status = StatusEnum::FAIL;
            $statisticDto->error = ErrorEnum::VALUE_FOR_PASSED_ERROR;
            return $statisticDto;
        }

        try {
            $dateTime = new DateTime('NOW');
            $dateTime->modify( '-1 ' . $period);
        } catch (ErrorException $exception) {
            $statisticDto->status = StatusEnum::FAIL;
            $statisticDto->error = ErrorEnum::INVALID_PERIOD_PARAM_ERROR;
            return $statisticDto;
        }

        $statisticData = $this->statisticRepository->getRecent($dateTime);
        $statisticDto->mostPopularProvider = (string) array_shift($statisticData[0]['topK(1)(provider_name)']);
        return $statisticDto;
    }
}
