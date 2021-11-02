<?php

namespace App\Services\Weather\Contracts;

use App\Services\Weather\Dto\GeoPositionDto;

interface GeoProviderInterface
{
    public function getGeoPositionByCity(string $city): GeoPositionDto;
}
