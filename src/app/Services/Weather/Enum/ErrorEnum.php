<?php

declare(strict_types=1);

namespace App\Services\Weather\Enum;

class ErrorEnum
{
    public const INVALID_PROVIDER = 'Unknown provider';
    public const GEO_POSITION_ERROR = 'Location is not defined';
    public const INTERNAL_ERROR = 'Data could not be retrieved';
    public const INVALID_PERIOD_PARAM_ERROR = 'Invalid period parameter given';
    public const VALUE_FOR_PASSED_ERROR = "The value for the 'period' key was not passed";
}
