<?php

namespace App\Services\Weather;

use App\Services\Weather\Dto\StatisticDto;
use DateTimeInterface;

interface GetStatisticActionInterface
{
    public function execute(string $period): StatisticDto;
}
