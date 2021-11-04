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
                city String,
                created_at DateTime
            )
            ENGINE = MergeTree()
            ORDER BY (created_at)
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
