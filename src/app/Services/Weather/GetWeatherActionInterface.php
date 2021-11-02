<?php

declare(strict_types=1);

namespace App\Services\Weather;

use App\Services\Weather\Dto\AverageWeatherDto;
use App\Services\Weather\Exceptions\ProviderNotFoundException;

interface GetWeatherActionInterface
{
    public function execute(string $providerName, string $city): AverageWeatherDto;
}
