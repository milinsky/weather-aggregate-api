<?php

declare(strict_types=1);

namespace App\Services\WeatherStatistic\Enum;

class PeriodTypeEnum
{
    public const DAY = 'day';
    public const MONTH = 'month';

    /**
     * @return string[]
     */
    public static function possibleValues(): array
    {
        return [
            self::DAY,
            self::MONTH,
        ];
    }
}
