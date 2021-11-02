<?php

declare(strict_types=1);

namespace App\Services\Weather\Contracts;

use App\Services\Weather\Dto\GeoPositionDto;
use App\Services\Weather\Dto\WeatherDto;

interface WeatherProviderInterface
{
    public function getWeatherByGeoPosition(GeoPositionDto $geoPositionDto): WeatherDto;
}
