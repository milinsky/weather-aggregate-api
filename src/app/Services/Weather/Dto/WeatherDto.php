<?php

declare(strict_types=1);

namespace App\Services\Weather\Dto;

class WeatherDto
{
    public string $status;
    public string $city;
    public float $temperature;
}
