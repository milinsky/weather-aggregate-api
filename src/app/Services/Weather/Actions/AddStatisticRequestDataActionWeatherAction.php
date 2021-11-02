<?php

declare(strict_types=1);

namespace App\Services\Weather\Actions;

use App\Services\Weather\AddStatisticDataActionInterface;
use App\Services\Weather\Enum\StatusEnum;
use App\Services\Weather\StatisticRepository;
use App\Services\Weather\Dto\AverageWeatherDto;

class AddStatisticRequestDataActionWeatherAction implements AddStatisticDataActionInterface
{
    private StatisticRepository $statisticRepository;

    public function __construct(StatisticRepository $statisticRepository)
    {
        $this->statisticRepository = $statisticRepository;
    }

    public function execute(AverageWeatherDto $averageWeatherDto): void
    {
        $averageWeatherDto->status === StatusEnum::SUCCESS ??
        $this->statisticRepository->add($averageWeatherDto->provider);
    }
}
