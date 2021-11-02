<?php

declare(strict_types=1);

namespace App\Services\Weather\Dto;

use App\Services\Weather\Enum\StatusEnum;

class WeatherDto extends CommonDto
{
    public string $status;
    public string $city;
    public float $temperature;
}
