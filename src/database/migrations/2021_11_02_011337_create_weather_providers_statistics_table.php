<?php

use PhpClickHouseLaravel\Migration;

class CreateWeatherProvidersStatisticsTable extends Migration
{
    protected $connection = 'clickhouse';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        static::write('
            CREATE TABLE weather_requests_statistics (
                id UInt32,
                created_at DateTime,
                city String
            )
            ENGINE = MergeTree()
            ORDER BY (id)
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        static::write('DROP TABLE weather_requests_statistics');
    }
}
