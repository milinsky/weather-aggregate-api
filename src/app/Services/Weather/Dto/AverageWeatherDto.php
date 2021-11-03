<?php

declare(strict_types=1);

namespace App\Services\Weather\Dto;

class AverageWeatherDto
{
    public string $status;
    public array $providers;
    public string $city;
    public float $averageTemperature;
    public string $error;
}
