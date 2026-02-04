<?php
return [
    'db' => [
        'driver' => getenv('DB_DRIVER') ?: 'mysql',
        'host'   => getenv('DB_HOST') ?: 'localhost',
        'port'   => getenv('DB_PORT') ?: '3306',
        'name'   => getenv('DB_NAME') ?: 'taxi_app',
        'user'   => getenv('DB_USER') ?: 'root',
        'pass'   => getenv('DB_PASS') ?: ''
    ]
];
