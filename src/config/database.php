<?php

return [
    'default' => env('DB_CONNECTION', 'clickhouse'),
    'connections' => [
        'clickhouse' => [
            'driver' => 'clickhouse',
            'host' => env('CLICKHOUSE_HOST'),
            'port' => env('CLICKHOUSE_PORT','8123'),
            'database' => env('CLICKHOUSE_DATABASE','default'),
            'username' => env('CLICKHOUSE_USERNAME','default'),
            'password' => env('CLICKHOUSE_PASSWORD',''),
            'timeout_connect' => env('CLICKHOUSE_TIMEOUT_CONNECT',2),
            'timeout_query' => env('CLICKHOUSE_TIMEOUT_QUERY',2),
            'https' => (bool)env('CLICKHOUSE_HTTPS', null),
            'retries' => env('CLICKHOUSE_RETRIES', 0),
            'settings' => [ // optional
                'max_partitions_per_insert_block' => 300,
            ],
        ],
    ],
    'migrations' => 'migrations',
];
