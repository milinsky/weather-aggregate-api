<?php

declare(strict_types=1);

namespace App\Events;

use App\Services\Weather\Dto\AverageWeatherDto;

class WeatherByCityRequestedEvent extends Event
{
    public AverageWeatherDto $averageWeatherDto;

    public function __construct(AverageWeatherDto $averageWeatherDto)
    {
        $this->averageWeatherDto = $averageWeatherDto;
    }
}
