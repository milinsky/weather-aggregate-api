<?php

declare(strict_types=1);

namespace App\Services\Weather;

use App\Services\Weather\Dto\AverageWeatherDto;

interface AddStatisticDataActionInterface
{
    public function execute(AverageWeatherDto $averageWeatherDto): void;
}
