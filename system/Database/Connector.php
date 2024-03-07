<?php

namespace System\Database;

use Exception;
use PDO;
use PDOException;

class Connector
{
    public static function connect(): PDO
    {
        $config = config('database');
        $conn = null;

        if (!in_array($config['default'], PDO::getAvailableDrivers())) {
            throw new Exception('Exception: ' . $config['default'] . ' driver not found');
        }

        if ($config['default'] == 'sqlite') {
            $sqlite = $config['connections']['sqlite'];

            if (!file_exists($sqlite['database'])) {
                fopen($sqlite['database'], 'w');
            }

            $conn = self::createPdoConnection(
                $sqlite['driver'] . ':' . $sqlite['database'],
                null,
                null,
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } else if ($config['default'] == 'mysql') {
            $mysql = $config['connections']['mysql'];

            $conn = self::createPdoConnection(
                $mysql['driver'] . ':host=' . $mysql['host'] . ';port=' . $mysql['port'] . ';dbname=' . $mysql['database'],
                $mysql['username'],
                $mysql['password'],
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_general_ci",
                ]
            );
        } else if ($config['defalut'] == 'pgsql') {
            $pgsql = $config['connections']['pgsql'];

            $conn = self::createPdoConnection(
                $pgsql['driver'] . ':host=' . $pgsql['host'] . ';port=' . $pgsql['port'] . ';dbname=' . $pgsql['database'] . ';sslmode=' . $pgsql['sslmode'],
                $pgsql['username'],
                $pgsql['password'],
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } else if ($config['defalut'] == 'sqlsrv') {
            $sqlsrv = $config['connections']['sqlsrv'];

            $conn = self::createPdoConnection(
                $sqlsrv['driver'] . ':Server=' . $sqlsrv['host'] . ';Database=' . $sqlsrv['database'],
                $sqlsrv['username'],
                $sqlsrv['password'],
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } else {
            throw new Exception('Exception: database driver not found');
        }
        return $conn;
    }

    private static function createPdoConnection($dsn, $username, $password, $options)
    {
        try {
            $conn = new PDO($dsn, $username, $password, $options);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception('Exception: database connection failed');
        }
        return $conn;
    }
}
