<?php

declare(strict_types=1);

namespace App\Services\Weather\Actions;

use App\Services\Weather\Dto\StatisticDto;
use App\Services\Weather\Enum\ErrorEnum;
use App\Services\Weather\Enum\PeriodTypeEnum;
use App\Services\Weather\Enum\StatusEnum;
use App\Services\Weather\GetStatisticActionInterface;
use App\Services\Weather\StatisticRepository;
use DateTime;

use function in_array;
use function array_count_values;
use function arsort;

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
            $statisticDto->error = ErrorEnum::VALUE_FOR_PERIOD_PASSED_ERROR;
            return $statisticDto;
        }

        if (!in_array($period, PeriodTypeEnum::possibleValues())) {
            $statisticDto->status = StatusEnum::FAIL;
            $statisticDto->error = ErrorEnum::INVALID_PERIOD_PARAM_ERROR;
            return $statisticDto;
        }

        $dateTime = new DateTime('NOW');
        $dateTime->modify( '-1 ' . $period);

        $statisticData = $this->statisticRepository->getRecentByPeriod($dateTime);
        $statisticDto->status = StatusEnum::SUCCESS;
        $statisticDto->mostPopularRequests = $this->getMostPopular($statisticData);

        return $statisticDto;
    }

    private function getMostPopular(array $statisticData): array
    {
        $listData = [];

        foreach ($statisticData as $data) {
            $listData[] = $data['city'];
        }

        $countValues = array_count_values($listData);;

        arsort($countValues);

        return $countValues;
    }
}
