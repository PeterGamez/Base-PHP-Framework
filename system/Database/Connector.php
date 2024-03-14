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

        if (extension_loaded("pdo_" . $config['default']) == false) {
            throw new Exception('Exception: ' . $config['default'] . ' driver not found');
        }

        if ($config['default'] == 'mysql') {
            $mysql = $config['connections']['mysql'];

            $conn = self::createPdoConnection(
                $mysql['driver'] . ':host=' . $mysql['host'] . ';port=' . $mysql['port'] . ';',
                $mysql['username'],
                $mysql['password'],
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $mysql['charset'] . " COLLATE " . $mysql['collation']
                ]
            );
            $conn->exec("CREATE DATABASE IF NOT EXISTS " . $mysql['database'] . " CHARACTER SET " . $mysql['charset'] . " COLLATE " . $mysql['collation']);
            $conn->exec("USE " . $mysql['database']);
        } else {
            throw new Exception('Exception: ' . $config['default'] . ' driver not found');
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
