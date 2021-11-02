<?php

declare(strict_types=1);

namespace App\Services\WeatherStatistic;

use DateTimeInterface;
use Illuminate\Support\Facades\DB;
use ClickHouseDB\Client;

class StatisticRepository
{
    public function add(string $providerName): void
    {
        /** @var \ClickHouseDB\Client $client */
        $client = DB::connection('clickhouse')->getClient();

        $this->createTable($client);

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

        $this->createTable($client);

        $statement = $client->select('
            SELECT topK(1)(provider_name)
            FROM weather_providers_statistics
            WHERE created_at < :created_at',
            ['created_at' => $dateTime->getTimestamp()]
         );
        return $statement->rows();
    }

    /**
     * @todo костыль из за неработающего механизма миграций в стороннем пакете
     */
    private function createTable(Client $client): void
    {
        $client->write('
            CREATE TABLE IF NOT EXISTS weather_providers_statistics (
                id UInt32,
                provider_name String,
                created_at DateTime
            )
            ENGINE = MergeTree()
            ORDER BY (id)
        ');
    }
}
