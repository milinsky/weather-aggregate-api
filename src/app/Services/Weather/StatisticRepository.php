<?php

declare(strict_types=1);

namespace App\Services\Weather;

use DateTimeInterface;
use Illuminate\Support\Facades\DB;
use ClickHouseDB\Client;

class StatisticRepository
{
    public function add(string $city): void
    {
        /** @var \ClickHouseDB\Client $client */
        $client = DB::connection('clickhouse')->getClient();

        $this->createTable($client);

        $client->insert(
            'weather_requests_statistics',
            [
                [$city ,time()],
            ],
            ['city', 'created_at']
        );
    }

    public function getRecentByPeriod(DateTimeInterface $dateTime)
    {
        /** @var \ClickHouseDB\Client $client */
        $client = DB::connection('clickhouse')->getClient();

        $this->createTable($client);

        $statement = $client->select('
            SELECT city
            FROM weather_requests_statistics
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
             CREATE TABLE IF NOT EXISTS weather_requests_statistics (
                 id UInt32,
                 city String,
                 created_at DateTime
             )
             ENGINE = MergeTree()
             ORDER BY (id)
         ');
    }
}
