<?php

declare(strict_types=1);

namespace App\Services\Weather;

use DateTimeInterface;
use Illuminate\Support\Facades\DB;
use ClickHouseDB\Client;

class StatisticRepository
{
    public function add(string $providerName): void
    {
        /** @var \ClickHouseDB\Client $client */
        $client = DB::connection('clickhouse')->getClient();

        $client->insert(
            'weather_providers_statistics',
            [
                [$providerName ,time()],
            ],
            ['provider_name', 'created_at']
        );
    }

    public function getRecent(DateTimeInterface $dateTime)
    {
        /** @var \ClickHouseDB\Client $client */
        $client = DB::connection('clickhouse')->getClient();

        $statement = $client->select('
            SELECT topK(1)(provider_name)
            FROM weather_providers_statistics
            WHERE created_at < :created_at',
            ['created_at' => $dateTime->getTimestamp()]
         );
        return $statement->rows();
    }
}
