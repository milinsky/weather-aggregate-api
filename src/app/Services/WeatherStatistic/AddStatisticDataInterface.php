<?php

declare(strict_types=1);

namespace App\Services\WeatherStatistic;

interface AddStatisticDataInterface
{
    public function execute(string $providerName): void;
}
