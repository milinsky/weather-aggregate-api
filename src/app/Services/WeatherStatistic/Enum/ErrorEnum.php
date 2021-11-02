<?php

declare(strict_types=1);

namespace App\Services\WeatherStatistic\Enum;

class ErrorEnum
{
    public const INVALID_PERIOD_PARAM_ERROR = 'Invalid period parameter given';
    public const VALUE_FOR_PASSED_ERROR = "The value for the 'period' key was not passed";
}
