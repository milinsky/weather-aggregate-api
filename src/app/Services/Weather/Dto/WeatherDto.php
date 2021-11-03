<?php

declare(strict_types=1);

namespace App\Services\Weather\Dto;

class WeatherDto
{
    private string $status;
    public float $temperature;

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
