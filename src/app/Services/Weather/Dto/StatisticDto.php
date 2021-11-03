<?php

declare(strict_types=1);

namespace App\Services\Weather\Dto;

class StatisticDto
{
    public string $status;
    public string $error;
    public array $mostPopularRequests;
}
