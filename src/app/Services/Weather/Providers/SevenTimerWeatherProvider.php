<?php

declare(strict_types=1);

namespace App\Services\Weather\Providers;

use App\Services\Weather\Contracts\WeatherProviderInterface;
use App\Services\Weather\Dto\AverageWeatherDto;

class SevenTimerWeatherProvider implements WeatherProviderInterface
{
    public function getWeatherByGeoPosition(string $city): AverageWeatherDto
    {
        return new AverageWeatherDto();
    }
}
