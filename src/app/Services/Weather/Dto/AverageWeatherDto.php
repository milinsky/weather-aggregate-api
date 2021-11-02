<?php

declare(strict_types=1);

namespace App\Services\Weather\Dto;

class AverageWeatherDto extends CommonDto
{
    public string $status;
    public string $provider;
    public string $city;
    public float $temperature;
    public float $averageTemperature;
    public string $error;
}
