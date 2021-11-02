<?php

declare(strict_types=1);

namespace App\Services\Weather\Dto;

class GeoPositionDto
{
    public string $status;
    public string $city;
    public int $placeId;
    public string $latitude;
    public string $longitude;
}
