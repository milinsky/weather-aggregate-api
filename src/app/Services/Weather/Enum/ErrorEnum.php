<?php

declare(strict_types=1);

namespace App\Services\Weather\Enum;

class ErrorEnum
{
    public const INVALID_PROVIDER = 'invalid';
    public const GEO_POSITION_ERROR = 'Геолокация полная хуйня';
    public const INTERNAL_ERROR = 'alkash';
}
