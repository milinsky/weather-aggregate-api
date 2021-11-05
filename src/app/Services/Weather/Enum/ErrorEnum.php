<?php

declare(strict_types=1);

namespace App\Services\Weather\Enum;

class ErrorEnum
{
    public const INTERNAL_ERROR = 'Internal error';
    public const GEO_POSITION_ERROR = 'Location is not defined';
    public const INVALID_PERIOD_PARAM_ERROR = 'Invalid period parameter given';
    public const VALUE_FOR_RERIOD_PASSED_ERROR = "The value for the 'period' key was not passed";
    public const VALUE_FOR_CITY_PASSED_ERROR = "The value for the 'city' key was not passed";
}
