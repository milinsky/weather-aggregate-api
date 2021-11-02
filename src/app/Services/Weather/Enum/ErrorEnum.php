<?php

declare(strict_types=1);

namespace App\Services\Weather\Enum;

class ErrorEnum
{
    public const INVALID_PROVIDER = 'Unknown provider';
    public const GEO_POSITION_ERROR = 'Location is not defined';
    public const INTERNAL_ERROR = 'Data could not be retrieved';
}
