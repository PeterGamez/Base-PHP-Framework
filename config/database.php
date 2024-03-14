<?php

return [
    /**
     * Default Database connection
     */

    "default" => env("DB_CONNECTION", "pgsql"),

    /**
     * Database Connections
     */

    "connections" => [
        "mysql" => [
            "driver" => "mysql",
            "host" => env("DB_HOST", "127.0.0.1"),
            "port" => env("DB_PORT", "3306"),
            "database" => env("DB_DATABASE", "forge"),
            "username" => env("DB_USERNAME", "root"),
            "password" => env("DB_PASSWORD", ""),
            "charset" => "utf8mb4",
            "collation" => "utf8mb4_general_ci"
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
