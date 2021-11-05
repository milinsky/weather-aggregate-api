<?php

use App\Services\Weather\Providers\OpenMeteoWeatherProvider;
use App\Services\Weather\Providers\SevenTimerWeatherProvider;

return [
    'geo' => [
        'Nominatim' => [
            'params' => [
                'base_url' => 'https://nominatim.openstreetmap.org/search.php?accept-language=ru,en&format=jsonv2&limit=1',
            ],
        ],
    ],
    'providers' => [
        'OpenMeteo' => [
            'class' => OpenMeteoWeatherProvider::class,
            'params' => [
                'base_url' => 'https://api.open-meteo.com/v1/forecast?&current_weather=true',
            ]
        ],
        '7Timer' => [
            'class' => SevenTimerWeatherProvider::class,
            'params' => [
                'base_url' => 'http://www.7timer.info/bin/api.pl?product=meteo&output=json',
            ]
        ],
    ],
];
