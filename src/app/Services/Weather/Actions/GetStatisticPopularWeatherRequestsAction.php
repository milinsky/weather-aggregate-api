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

        if (!in_array($period, PeriodTypeEnum::possibleValues())) {
            $statisticDto->status = StatusEnum::FAIL;
            $statisticDto->error = ErrorEnum::INVALID_PERIOD_PARAM_ERROR;
            return $statisticDto;
        }

        $dateTime = new DateTime('NOW');
        $dateTime->modify( '-1 ' . $period);

        $statisticData = $this->statisticRepository->getRecent($dateTime);
        $statisticDto->status = StatusEnum::SUCCESS;
        $statisticDto->mostPopularProvider = (string) array_shift($statisticData[0]['topK(1)(provider_name)']);

        return $statisticDto;
    }
}
