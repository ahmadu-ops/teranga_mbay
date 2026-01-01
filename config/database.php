<?php

return [

    'default' => env('DB_CONNECTION', 'oracle'),

    'connections' => [

        'oracle' => [
            'driver'         => 'oracle',
            'tns'            => env('DB_TNS', ''),
            'host'           => env('DB_HOST', '127.0.0.1'),
            'port'           => env('DB_PORT', '1521'),
            'database'       => env('DB_DATABASE', 'XEPDB1'),
            'service_name'   => env('DB_SERVICE_NAME', 'XEPDB1'),
            'username'       => env('DB_USERNAME', ''),
            'password'       => env('DB_PASSWORD', ''),
            'charset'        => env('DB_CHARSET', 'AL32UTF8'),
            'prefix'         => env('DB_PREFIX', ''),
            'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
            'edition'        => env('DB_EDITION', 'ora$base'),
            'server_version' => env('DB_SERVER_VERSION', '19c'),
            'dynamic'        => [],
        ],

        // Garder SQLite pour les tests si nécessaire
        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => database_path('database.sqlite'),
            'prefix'   => '',
        ],

    ],

    'migrations' => 'migrations',

];