<?php

return [
    /**
     * Default Database connection
     */

    "default" => env("DB_CONNECTION", "mysql"),

    /**
     * Database Connections
     */

    "connections" => [
        "sqlite" => [
            "driver" => "sqlite",
            "database" => __ROOT__ . "/database/database.sqlite"
        ],
        "mysql" => [
            "driver" => "mysql",
            "host" => env("DB_HOST", "127.0.0.1"),
            "port" => env("DB_PORT", "3306"),
            "database" => env("DB_DATABASE", "forge"),
            "username" => env("DB_USERNAME", "forge"),
            "password" => env("DB_PASSWORD", "")
        ],
        "pgsql" => [
            "driver" => "pgsql",
            "host" => env("DB_HOST", "127.0.0.1"),
            "port" => env("DB_PORT", "5432"),
            "database" => env("DB_DATABASE", "forge"),
            "username" => env("DB_USERNAME", "forge"),
            "password" => env("DB_PASSWORD", ""),
            "sslmode" => "prefer"
        ],
        "sqlsrv" => [
            "driver" => "sqlsrv",
            "host" => env("DB_HOST", "127.0.0.1"),
            "port" => env("DB_PORT", "1433"),
            "database" => env("DB_DATABASE", "forge"),
            "username" => env("DB_USERNAME", "forge"),
            "password" => env("DB_PASSWORD", ""),
        ]
    ],

    /**
     * Database Trashing
     */

    "trash" => [
        "enabled" => false,
        "day" => 30 // delete trash after 30 days
    ]
];
